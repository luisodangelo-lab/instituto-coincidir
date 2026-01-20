<?php

namespace App\Services;

use App\Models\Cohort;
use App\Models\Enrollment;
use App\Models\Installment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function enrollByDniAndGenerateInstallments(
        string $dni,
        int $cohortId,
        ?int $overrideInstallmentsCount = null,
        ?float $overrideTotal = null,
        ?string $firstDueDate = null
    ): Enrollment {
        return DB::transaction(function () use ($dni, $cohortId, $overrideInstallmentsCount, $overrideTotal, $firstDueDate) {

            $user = User::where('dni', $dni)->firstOrFail();
            $cohort = Cohort::with('course')->findOrFail($cohortId);

            $enrollment = Enrollment::firstOrCreate(
                ['user_id' => $user->id, 'cohort_id' => $cohort->id],
                ['status' => 'inscripto', 'enrolled_at' => now()]
            );

            // Si ya tiene cuotas creadas, no regeneramos (para evitar duplicar)
            if ($enrollment->installments()->count() > 0) {
                return $enrollment;
            }

            $n = $overrideInstallmentsCount ?: (int)$cohort->installments_count;
            $n = max(1, min(10, $n));

            $total = $overrideTotal !== null ? (float)$overrideTotal : (float)$cohort->price_total;
            $total = max(0, $total);

            // Split en partes iguales y ajusta el último por redondeo
            $base = $n > 0 ? round($total / $n, 2) : 0;
            $sum = 0;

            $due = $firstDueDate ? \Carbon\Carbon::parse($firstDueDate) : ($cohort->start_date ? $cohort->start_date->copy() : now());

            for ($i = 1; $i <= $n; $i++) {
                $amount = ($i < $n) ? $base : round($total - $sum, 2);
                $sum += ($i < $n) ? $base : $amount;

                Installment::create([
                    'enrollment_id' => $enrollment->id,
                    'number' => $i,
                    'due_date' => $due->copy()->addMonths($i - 1)->toDateString(),
                    'amount_due' => $amount,
                    'amount_paid' => 0,
                    'status' => 'unpaid',
                ]);
            }

            return $enrollment;
        });
    }
}
