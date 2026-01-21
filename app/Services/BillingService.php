<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\WalletMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BillingService
{
    public function approvePayment(Payment $payment, int $actorUserId): void
    {
        DB::transaction(function () use ($payment, $actorUserId) {

            if ($payment->status !== 'pending_review') return;

            $payment->update([
                'status'      => 'approved',
                'approved_at' => now(),
                'approved_by' => $actorUserId,
            ]);

            $remaining = round((float)$payment->amount, 2);

            // 1) Imputar a cuotas si hay matrícula
            if (!empty($payment->enrollment_id)) {
                $enr = $payment->enrollment()->first();

                if ($enr) {
                    $installments = $enr->installments()
                        ->orderBy('number')
                        ->lockForUpdate()
                        ->get();

                    foreach ($installments as $inst) {
                        $need = round((float)$inst->amount_due - (float)$inst->amount_paid, 2);
                        if ($need <= 0) continue;
                        if ($remaining <= 0) break;

                        $alloc = round(min($need, $remaining), 2);

                        PaymentAllocation::create([
                            'payment_id'     => $payment->id,
                            'installment_id' => $inst->id,
                            'amount_applied' => $alloc,
                        ]);

                        $inst->amount_paid = round((float)$inst->amount_paid + $alloc, 2);
                        $inst->status = ($inst->amount_paid >= (float)$inst->amount_due) ? 'paid' : 'partial';
                        $inst->save();

                        $remaining = round($remaining - $alloc, 2);
                    }
                }
            }

            // 2) Sobrante => saldo a favor (wallet)
            if ($remaining > 0) {

                $wm = [
                    'user_id'    => $payment->user_id,
                    'payment_id' => $payment->id,
                    'amount'     => round($remaining, 2),
                    'notes'      => 'Sobrante de pago aprobado',
                ];

                // Tu DB puede tener 'reason' o no. Usamos la que exista.
                if (Schema::hasColumn('wallet_movements', 'reason')) {
                    $wm['reason'] = 'overpay';
                } elseif (Schema::hasColumn('wallet_movements', 'type')) {
                    $wm['type'] = 'overpay';
                } elseif (Schema::hasColumn('wallet_movements', 'concept')) {
                    $wm['concept'] = 'overpay';
                }

                WalletMovement::create($wm);

                $u = $payment->user()->lockForUpdate()->first();
                if ($u) {
                    $u->wallet_balance = round((float)$u->wallet_balance + $remaining, 2);
                    $u->save();
                }
            }
        });
    }

    public function registerRefund(Payment $original, float $amount, int $actorUserId, ?string $notes = null): void
    {
        DB::transaction(function () use ($original, $amount, $actorUserId, $notes) {

            $amount = round(max(0, $amount), 2);
            if ($amount <= 0) return;

            $refund = Payment::create([
                'user_id'              => $original->user_id,
                'enrollment_id'         => $original->enrollment_id,
                'type'                 => 'refund',
                'provider'             => $original->provider,
                'method'               => $original->method,
                'amount'               => $amount,
                'status'               => 'approved',
                'reference'            => 'Refund de pago #'.$original->id,
                'refund_of_payment_id' => $original->id,
                'refunded_at'          => now(),
                'refunded_by'          => $actorUserId,
                'notes'                => $notes ?: 'Devolución',
            ]);

            $wm = [
                'user_id'    => $original->user_id,
                'payment_id' => $refund->id,
                'amount'     => -$amount,
                'notes'      => $notes ?: 'Devolución registrada',
            ];

            if (Schema::hasColumn('wallet_movements', 'reason')) {
                $wm['reason'] = 'refund';
            } elseif (Schema::hasColumn('wallet_movements', 'type')) {
                $wm['type'] = 'refund';
            } elseif (Schema::hasColumn('wallet_movements', 'concept')) {
                $wm['concept'] = 'refund';
            }

            WalletMovement::create($wm);

            $u = $original->user()->lockForUpdate()->first();
            if ($u) {
                $u->wallet_balance = round((float)$u->wallet_balance - $amount, 2);
                $u->save();
            }
        });
    }
}
