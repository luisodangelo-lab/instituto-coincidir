<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\WalletMovement;
use Illuminate\Support\Facades\DB;

class BillingService
{
    public function approvePayment(Payment $payment, int $actorUserId): void
    {
        DB::transaction(function () use ($payment, $actorUserId) {

            if ($payment->status !== 'pending_review') return;

            $payment->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $actorUserId,
            ]);

            $remaining = (float)$payment->amount;

            // Si está asociado a matrícula: imputar automático a cuotas impagas
            if ($payment->enrollment_id) {
                $enr = $payment->enrollment()->with(['installments'])->first();

                foreach ($enr->installments as $inst) {
                    $need = (float)$inst->amount_due - (float)$inst->amount_paid;
                    if ($need <= 0) continue;
                    if ($remaining <= 0) break;

                    $alloc = min($need, $remaining);

                    PaymentAllocation::create([
                        'payment_id' => $payment->id,
                        'installment_id' => $inst->id,
                        'amount' => $alloc,
                        'created_by' => $actorUserId,
                    ]);

                    $inst->amount_paid = round((float)$inst->amount_paid + $alloc, 2);

                    if ($inst->amount_paid >= (float)$inst->amount_due) $inst->status = 'paid';
                    else $inst->status = 'partial';

                    $inst->save();

                    $remaining = round($remaining - $alloc, 2);
                }
            }

            // Sobrante => saldo a favor (wallet) y allocation sin cuota (para trazabilidad)
            if ($remaining > 0) {
                PaymentAllocation::create([
                    'payment_id' => $payment->id,
                    'installment_id' => null,
                    'amount' => $remaining,
                    'created_by' => $actorUserId,
                ]);

                WalletMovement::create([
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'amount' => $remaining,
                    'reason' => 'overpay',
                    'notes' => 'Sobrante de pago aprobado',
                    'created_by' => $actorUserId,
                ]);

                // cache en users.wallet_balance
                $u = $payment->user()->lockForUpdate()->first();
                $u->wallet_balance = round((float)$u->wallet_balance + $remaining, 2);
                $u->save();
            }
        });
    }

    public function registerRefund(Payment $original, float $amount, int $actorUserId, ?string $notes = null): void
    {
        DB::transaction(function () use ($original, $amount, $actorUserId, $notes) {

            $amount = max(0, round($amount, 2));
            if ($amount <= 0) return;

            $refund = Payment::create([
                'user_id' => $original->user_id,
                'enrollment_id' => $original->enrollment_id,
                'type' => 'refund',
                'provider' => $original->provider,
                'method' => $original->method,
                'amount' => $amount,
                'status' => 'approved',
                'reference' => 'Refund de pago #'.$original->id,
                'refund_of_payment_id' => $original->id,
                'refunded_at' => now(),
                'refunded_by' => $actorUserId,
                'notes' => $notes ?: 'Devolución',
            ]);

            // MVP: la devolución descuenta del saldo a favor (puede quedar negativo si no alcanza)
            WalletMovement::create([
                'user_id' => $original->user_id,
                'payment_id' => $refund->id,
                'amount' => -$amount,
                'reason' => 'refund',
                'notes' => $notes ?: 'Devolución registrada',
                'created_by' => $actorUserId,
            ]);

            $u = $original->user()->lockForUpdate()->first();
            $u->wallet_balance = round((float)$u->wallet_balance - $amount, 2);
            $u->save();
        });
    }
}
