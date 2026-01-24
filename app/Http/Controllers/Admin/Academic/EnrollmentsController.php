<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\InstallmentService;
use Illuminate\Http\Request;

class EnrollmentsController extends Controller
{
    public function create()
    {
        $cohorts = Cohort::orderByDesc('id')->get();
        return view('admin.academic.enrollments_create', compact('cohorts'));
    }

public function preinscriptions()
{
    $rows = \App\Models\Enrollment::with(['user','cohort','cohort.course'])
        ->whereIn('status', ['preinscripto','pendiente_pago'])
        ->orderByRaw("FIELD(status,'pendiente_pago','preinscripto')")
        ->orderByDesc('updated_at')
        ->paginate(30);

    return view('admin.academic.preinscriptions.index', compact('rows'));
}

public function markInscripto(\App\Models\Enrollment $enrollment)
{
    // no tocar si ya está inscripto o baja
    if (!in_array($enrollment->status, ['inscripto','baja'], true)) {
        $enrollment->status = 'inscripto';
        $enrollment->save();
    }

    return back()->with('ok', 'Inscripción marcada como INSCRIPTO.');
}


    public function store(Request $request, InstallmentService $svc)
    {
        $data = $request->validate([
            'dni' => ['required','string','max:20'],
            'cohort_id' => ['required','integer','exists:cohorts,id'],
            'generate_installments' => ['nullable','boolean'],
        ]);

        $user = User::where('dni', $data['dni'])->first();
        if (!$user) {
            return back()->withInput()->with('error', 'No existe un usuario con ese DNI.');
        }

        $enr = Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'cohort_id' => (int)$data['cohort_id']],
            ['status' => 'active']
        );

        if ($request->boolean('generate_installments')) {
            $svc->generateForEnrollment($enr, false);
        }

        return redirect()->route('admin.academic.enrollments.create')
            ->with('ok', 'Matrícula creada/confirmada para DNI '.$user->dni.'.');
    }

    public function generateInstallments(Request $request, Enrollment $enrollment, InstallmentService $svc)
    {
        $overwrite = $request->boolean('overwrite');
        $created = $svc->generateForEnrollment($enrollment, $overwrite);

        return back()->with('ok', $created
            ? "Cuotas generadas: {$created}"
            : "No se generaron cuotas (ya existían).");
    }

    public function preinscriptions()
    {
        $rows = \App\Models\Enrollment::with(['user','cohort','cohort.course'])
            ->whereIn('status', ['preinscripto','pendiente_pago'])
            ->orderByRaw("FIELD(status,'pendiente_pago','preinscripto')")
            ->orderByDesc('updated_at')
            ->paginate(30);

        return view('admin.academic.preinscriptions.index', compact('rows'));
    }

    public function markInscripto(\App\Models\Enrollment $enrollment)
    {
        if (!in_array($enrollment->status, ['inscripto','baja'], true)) {
            $enrollment->status = 'inscripto';
            $enrollment->save();
        }

        return back()->with('ok', 'Inscripción marcada como INSCRIPTO.');
    }


}
