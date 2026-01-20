<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Cohort;
use App\Models\User;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;

class EnrollmentsController extends Controller
{
    public function index(Request $r)
    {
        $q = Enrollment::query()->with(['user','cohort.course']);

        if ($r->filled('dni')) {
            $q->whereHas('user', fn($u)=>$u->where('dni',$r->dni));
        }
        if ($r->filled('course_id')) {
            $q->whereHas('cohort', fn($c)=>$c->where('course_id',$r->course_id));
        }
        if ($r->filled('status')) {
            $q->where('status',$r->status);
        }

        $enrollments = $q->orderByDesc('id')->paginate(20);

        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $cohorts = Cohort::with('course')->where('is_active',1)->orderByDesc('id')->get();
        return view('admin.enrollments.create', compact('cohorts'));
    }

    public function store(Request $r, EnrollmentService $svc)
    {
        $r->validate([
            'dni' => ['required','string','max:20'],
            'cohort_id' => ['required','integer','exists:cohorts,id'],
            'installments_count' => ['nullable','integer','min:1','max:10'],
            'price_total' => ['nullable','numeric','min:0'],
            'first_due_date' => ['nullable','date'],
        ]);

        // Asegurar usuario existente (si querés, acá luego hacemos “crear si no existe”)
        User::where('dni',$r->dni)->firstOrFail();

        $enr = $svc->enrollByDniAndGenerateInstallments(
            $r->dni,
            (int)$r->cohort_id,
            $r->installments_count ? (int)$r->installments_count : null,
            $r->price_total !== null ? (float)$r->price_total : null,
            $r->first_due_date
        );

        return redirect()->route('admin.enrollments.show', $enr)->with('ok','Matrícula creada y cuotas generadas.');
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user','cohort.course','installments','payments']);
        return view('admin.enrollments.show', compact('enrollment'));
    }
}
