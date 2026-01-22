<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PreinscriptionController extends Controller
{
    public function store(Request $request, Cohort $cohort)
    {
        if (!$this->cohortIsOpen($cohort)) {
            return back()->withErrors(['cohort' => 'Esta cohorte no está abierta para preinscripción.']);
        }

        $user = $request->user();

        $plan = [
            'price_total' => (float) $cohort->price_total,
            'installments_count' => (int) $cohort->installments_count,
            'installment_due_day' => (int) ($cohort->installment_due_day ?? 10),
            'installment_frequency' => $cohort->installment_frequency ?? 'monthly',
            'first_due_date' => $cohort->first_due_date,
            'billing_mode' => $cohort->billing_mode ?? 'full',
            'period_months' => $cohort->period_months,
            'generate_horizon_months' => $cohort->generate_horizon_months,
        ];

        try {
            Enrollment::create([
                'user_id' => $user->id,
                'cohort_id' => $cohort->id,
                'status' => 'preinscripto',
                'source' => 'web',
                'price_snapshot' => $cohort->price_total,
                'plan_snapshot' => $plan,
                'enrolled_at' => now(),
            ]);
        } catch (QueryException $e) {
            return back()->withErrors(['cohort' => 'Ya tenés una inscripción para esta cohorte.']);
        }

        return redirect()->route('catalog.show', $cohort->course)
            ->with('ok', '¡Listo! Quedaste preinscripto. En breve te contactaremos con los datos de pago.');
    }

    private function cohortIsOpen(Cohort $cohort): bool
    {
        if (!$cohort->enrollment_open) return false;

        if ($cohort->enrollment_open_at && now()->lt($cohort->enrollment_open_at)) return false;
        if ($cohort->enrollment_close_at && now()->gt($cohort->enrollment_close_at)) return false;

        return true;
    }
}
