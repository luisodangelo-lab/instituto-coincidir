<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Installment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InstallmentService
{
    public function generateForEnrollment(Enrollment $enr, bool $overwrite = false): int
    {
        return DB::transaction(function () use ($enr, $overwrite) {
            $enr->loadMissing('cohort');
            $cohort = $enr->cohort;

            if (!$cohort) return 0;

            if ($overwrite) {
                Installment::where('enrollment_id', $enr->id)->delete();
            } else {
                if (Installment::where('enrollment_id', $enr->id)->exists()) {
                    return 0; // ya tiene
                }
            }

            $total = (float)$cohort->price_total;
            $n = max(1, (int)$cohort->installments_count);
            $base = round($total / $n, 2);

            $sum = 0.0;
            $start = $cohort->start_date ? Carbon::parse($cohort->start_date) : now()->startOfMonth();
            $day = (int)($cohort->installment_due_day ?: 10);

            for ($i = 1; $i <= $n; $i++) {
                $amount = ($i === $n) ? round($total - $sum, 2) : $base;
                $sum = round($sum + $amount, 2);

                $due = $start->copy()->addMonths($i - 1)->startOfMonth();
                $dueDay = min($day, $due->daysInMonth);
                $due = $due->day($dueDay);

                Installment::create([
                    'enrollment_id' => $enr->id,
                    'number' => $i,
                    'due_date' => $due->toDateString(),
                    'amount_due' => $amount,
                    'amount_paid' => 0,
                    'status' => 'unpaid',
                ]);
            }

            return $n;
        });
    }
}
