<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\WalletMovement;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    public static function approvePayment(Payment $payment, int $actorUserId): void
    {
        DB::transaction(function () use ($payment, $actorUserId) {

            if ($payment->status === 'approved') return;
            if ($payment->type !== 'payment') {
                throw new \RuntimeException('Solo se pueden aprobar pagos tipo payment.');
            }

            $payment->status = 'approved';
            $payment->approved_by = $actorUserId;
            $payment->approved_at = now();
            $payment->accounting_at = $payment->paid_at ?: now(); // contable por acreditación/aprobación
            $payment->save();

            // limpiar allocations si hubiera reintento
            PaymentAllocation::where('payment_id', $payment->id)->delete();

            $remainingCents = (int) round(((float)$payment->amount) * 100);

            // imputar a cuotas (viejas primero)
            $installments = $payment->enrollment
                ? $payment->enrollment->installments()->orderBy('number')->get()
                : collect();

            foreach ($installments as $inst) {
                if ($remainingCents <= 0) break;
                if (in_array($inst->status, ['paid','waived'], true)) continue;

                $dueCents  = (int) round(((float)$inst->amount_due) * 100);
                $paidCents = (int) round(((float)$inst->amount_paid) * 100);
                $bal = max(0, $dueCents - $paidCents);
                if ($bal <= 0) continue;

                $apply = min($bal, $remainingCents);

                PaymentAllocation::create([
                    'payment_id' => $payment->id,
                    'installment_id' => $inst->id,
                    'amount_applied' => $apply / 100,
                ]);

                $newPaid = $paidCents + $apply;
                $inst->amount_paid = $newPaid / 100;

                if ($newPaid >= $dueCents) $inst->status = 'paid';
                else $inst->status = 'partial';

                $inst->save();

                $remainingCents -= $apply;
            }

            // sobrante => saldo a favor del alumno (wallet)
            if ($remainingCents > 0) {
                $credit = $remainingCents / 100;
                $user = $payment->user()->lockForUpdate()->first();

                $user->wallet_balance = ((float)$user->wallet_balance) + $credit;
                $user->save();

                WalletMovement::create([
                    'user_id' => $user->id,
                    'amount' => $credit,
                    'payment_id' => $payment->id,
                    'enrollment_id' => $payment->enrollment_id,
                    'created_by' => $actorUserId,
                    'notes' => 'Crédito por sobrante de pago',
                    'accounting_at' => $payment->accounting_at,
                ]);
            }
        });
    }

    public static function refundPayment(Payment $original, float $refundAmount, int $actorUserId, ?string $reason = null): Payment
    {
        return DB::transaction(function () use ($original, $refundAmount, $actorUserId, $reason) {

            if ($original->status !== 'approved' || $original->type !== 'payment') {
                throw new \RuntimeException('Solo se pueden devolver pagos aprobados.');
            }

            $refundCents = (int) round($refundAmount * 100);
            $origCents   = (int) round(((float)$original->amount) * 100);

            if ($refundCents <= 0 || $refundCents > $origCents) {
                throw new \RuntimeException('Importe de devolución inválido.');
            }

            // crear pago refund (monto NEGATIVO)
            $refund = Payment::create([
                'user_id' => $original->user_id,
                'enrollment_id' => $original->enrollment_id,
                'type' => 'refund',
                'refund_of_payment_id' => $original->id,
                'provider' => $original->provider,
                'provider_payment_id' => null,
                'method' => $original->method,
                'amount' => -1 * ($refundCents / 100),
                'reference' => $original->reference,
                'status' => 'approved',
                'paid_at' => now(),
                'accounting_at' => now(),

                'created_by' => $actorUserId,
                'refunded_by' => $actorUserId,
                'refunded_at' => now(),
                'notes' => $reason ?: 'Devolución',
            ]);

            $remaining = $refundCents;

            // 1) primero descontar de wallet generada por el pago original
            $walletCreditCents = (int) round(
                WalletMovement::where('payment_id', $original->id)->sum('amount') * 100
            );
            $walletDebitCents = (int) round(
                WalletMovement::where('payment_id', $refund->id)->sum('amount') * 100
            );
            $availableWalletFromOrig = max(0, $walletCreditCents + $walletDebitCents); // debit es negativo

            $takeFromWallet = min($remaining, $availableWalletFromOrig);
            if ($takeFromWallet > 0) {
                $user = $original->user()->lockForUpdate()->first();
                $user->wallet_balance = ((float)$user->wallet_balance) - ($takeFromWallet / 100);
                if ($user->wallet_balance < 0) $user->wallet_balance = 0; // no negativo en MVP
                $user->save();

                WalletMovement::create([
                    'user_id' => $user->id,
                    'amount' => -1 * ($takeFromWallet / 100),
                    'payment_id' => $refund->id,
                    'enrollment_id' => $original->enrollment_id,
                    'created_by' => $actorUserId,
                    'notes' => 'Débito por devolución (primero saldo a favor)',
                    'accounting_at' => $refund->accounting_at,
                ]);

                $remaining -= $takeFromWallet;
            }

            // 2) luego revertir cuotas imputadas por el pago original (últimas primero)
            if ($remaining > 0 && $original->enrollment) {
                $allocs = $original->allocations()
                    ->with('installment')
                    ->get()
                    ->sortByDesc(fn($a) => $a->installment->number);

                foreach ($allocs as $a) {
                    if ($remaining <= 0) break;

                    $inst = $a->installment;
                    $appliedCents = (int) round(((float)$a->amount_applied) * 100);
                    if ($appliedCents <= 0) continue;

                    $take = min($remaining, $appliedCents);

                    PaymentAllocation::create([
                        'payment_id' => $refund->id,
                        'installment_id' => $inst->id,
                        'amount_applied' => -1 * ($take / 100),
                    ]);

                    $paidCents = (int) round(((float)$inst->amount_paid) * 100);
                    $paidCents = max(0, $paidCents - $take);

                    $dueCents = (int) round(((float)$inst->amount_due) * 100);

                    $inst->amount_paid = $paidCents / 100;
                    if ($paidCents <= 0) $inst->status = 'pending';
                    elseif ($paidCents < $dueCents) $inst->status = 'partial';
                    else $inst->status = 'paid';

                    $inst->save();

                    $remaining -= $take;
                }
            }

            return $refund;
        });
    }
}
