<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Cohort;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;





class PublicEnrollmentController extends Controller
{
    public function show(Request $request, $course)
    {
        // Buscar curso por ID o por code (NO slug: tu DB no lo tiene)
        $course = Course::query()
            ->where('id', $course)
            ->orWhere('code', $course)
            ->firstOrFail();

        // Cohorte activa
        $cohort = Cohort::where('course_id', $course->id)
    ->where(function ($q) {
        $q->whereNull('end_date')
          ->orWhereDate('end_date', '>=', now()->toDateString());
    })
    ->orderByDesc('id')
    ->first();


        abort_unless($cohort, 404, 'No hay cohorte activa para este curso.');

        return view('public.enroll', compact('course', 'cohort'));
    }

public function store(Request $request, $course)
{
    $course = Course::query()
        ->where('id', $course)
        ->orWhere('code', $course)
        ->firstOrFail();

    // Cohorte "abierta": end_date >= hoy o end_date null (porque tu DB no tiene is_active)
    $cohort = Cohort::query()
        ->where('course_id', $course->id)
        ->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhereDate('end_date', '>=', now()->toDateString());
        })
        ->orderByDesc('id')
        ->first();

    abort_unless($cohort, 404, 'No hay cohorte abierta para este curso.');

    $data = $request->validate([
        'name' => ['required', 'string', 'min:3', 'max:120'],
        'dni'  => ['required', 'string', 'min:6', 'max:16'],
        'email' => ['required', 'email', 'max:190'],
        'phone_whatsapp' => ['required', 'string', 'min:8', 'max:30'],
    ]);

    $dni = preg_replace('/\D+/', '', $data['dni']);
    $wa  = preg_replace('/\D+/', '', $data['phone_whatsapp']);
    $email = strtolower(trim($data['email']));

    // 1) Buscar usuario por DNI
    $user = User::where('dni', $dni)->first();

    // 2) Si NO existe por DNI, validá que el email no esté tomado por otro DNI (evita 1062)
    if (!$user) {
        $byEmail = User::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($byEmail && preg_replace('/\D+/', '', (string)$byEmail->dni) !== $dni) {
            return back()->withErrors([
                'email' => 'Ese email ya está registrado con otro DNI. Usá otro email o contactá a administración.'
            ])->withInput();
        }
        $user = new User();
        $user->dni = $dni;
    } else {
        // Si existe por DNI y el email es distinto, evitamos mezclar personas
        if (!empty($user->email) && strtolower($user->email) !== $email) {
            return back()->withErrors([
                'email' => 'Ese DNI ya existe con otro email. Contactá a administración.'
            ])->withInput();
        }
    }

    // 3) Completar datos
    $user->name  = $data['name'];
    $user->email = $email;

    // Asignar solo si existen columnas (evita "Unknown column")
    if (Schema::hasColumn('users', 'phone_whatsapp')) {
        $user->phone_whatsapp = $wa;
    }

    if (Schema::hasColumn('users', 'account_state') && empty($user->account_state)) {
        $user->account_state = 'pending';
    }

    if (Schema::hasColumn('users', 'role') && empty($user->role)) {
        $user->role = 'alumno';
    }
// Si la tabla exige password (no default), asignamos uno aleatorio (se cambia en Primer acceso)
if (Schema::hasColumn('users', 'password') && empty($user->password)) {
    $user->password = Hash::make(Str::random(32));
}

    $user->save();

    // 4) Crear matrícula preinscripta (status solo si existe)
    $attrs = ['user_id' => $user->id, 'cohort_id' => $cohort->id];
    $defaults = [];

    if (Schema::hasColumn('enrollments', 'status')) {
        $defaults['status'] = 'preinscripto';
    }

    $enr = Enrollment::firstOrCreate($attrs, $defaults);

    if (Schema::hasColumn('enrollments', 'public_token') && empty($enr->public_token)) {
        $enr->public_token = Str::random(48);
    }

    if (Schema::hasColumn('enrollments', 'status') && !in_array($enr->status, ['inscripto','baja'], true)) {
        $enr->status = 'preinscripto';
    }

    $enr->save();

    // Si NO existe public_token en tu tabla, no puede funcionar /comprobante/{token}
    abort_unless(!Schema::hasColumn('enrollments','public_token') || !empty($enr->public_token), 500, 'Falta columna public_token en enrollments.');

    // 5) Mail (si falla, no rompe)
    $receiptUrl  = route('public.receipt.show', ['token' => $enr->public_token]);
    $cohortLabel = $cohort->name ?? (string) $cohort->id;

    try {
        Mail::raw(
            "Hola {$user->name}!\n\n"
            ."Recibimos tu PREINSCRIPCIÓN al curso: {$course->title}.\n"
            ."Cohorte: {$cohortLabel}\n\n"
            ."Para completar la inscripción:\n"
            ."1) Realizá el pago según las indicaciones.\n"
            ."2) Subí tu comprobante acá: {$receiptUrl}\n\n"
            ."Gracias!\nInstituto Coincidir",
            function ($m) use ($user, $course) {
                $m->to($user->email)->subject("Preinscripción recibida: {$course->title}");
            }
        );
    } catch (\Throwable $e) {
        Log::warning('Mail preinscripción falló', [
            'enrollment_id' => $enr->id ?? null,
            'user_id' => $user->id ?? null,
            'error' => $e->getMessage(),
        ]);
    }

    return redirect()->route('public.receipt.show', ['token' => $enr->public_token])
        ->with('ok', 'Preinscripción registrada. Ya podés subir tu comprobante.');
}


    public function showReceipt(string $token)
    {
        $enr = Enrollment::where('public_token', $token)->firstOrFail();
        $enr->load(['user', 'cohort', 'cohort.course']);

        return view('public.receipt_upload', ['enr' => $enr]);
    }

    public function storeReceipt(Request $request, string $token)
    {
        $enr = Enrollment::where('public_token', $token)->firstOrFail();
        $enr->load(['user', 'cohort', 'cohort.course']);

        $data = $request->validate([
            'receipt' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'],
        ]);

        $file = $data['receipt'];

        $path = $file->storeAs(
            'private/receipts/' . date('Y'),
            'enr_' . $enr->id . '_' . time() . '.' . $file->getClientOriginalExtension()
        );

        // Si estas columnas existen en tu tabla enrollments, perfecto:
        $enr->receipt_path = $path;
        $enr->receipt_original_name = $file->getClientOriginalName();
        $enr->receipt_uploaded_at = now();

        // Al subir comprobante: pasa a pendiente_pago si estaba preinscripto
        if ($enr->status === 'preinscripto') {
            $enr->status = 'pendiente_pago';
        }

        $enr->save();

        // Aviso simple (si falla mail, no rompe)
        try {
            Mail::raw(
                "¡Listo {$enr->user->name}!\n\nRecibimos tu comprobante para el curso {$enr->cohort->course->title}.\nEn breve administración lo verificará.\n\nInstituto Coincidir",
                function ($m) use ($enr) {
                    $m->to($enr->user->email)->subject("Comprobante recibido");
                }
            );
        } catch (\Throwable $e) {
            Log::warning('Mail comprobante falló', [
                'enrollment_id' => $enr->id ?? null,
                'user_id' => $enr->user_id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('ok', 'Comprobante subido. En breve se verificará tu inscripción.');
    }
}
