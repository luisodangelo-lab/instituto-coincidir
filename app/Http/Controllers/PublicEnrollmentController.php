<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Cohort;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PublicEnrollmentController extends Controller
{
    public function show(Request $request, $course)
    {
       $course = Course::where('id', $course)
    ->orWhere('code', $course)
    ->orWhere('slug', $course)
    ->firstOrFail();



        $cohort = Cohort::where('course_id', $course->id)
            ->where('is_active', 1)   // ajustá si tu columna se llama distinto
            ->orderByDesc('id')
            ->first();

        abort_unless($cohort, 404, 'No hay cohorte activa para este curso.');

        return view('public.enroll', compact('course','cohort'));
    }

    public function store(Request $request, $course)
    {
        $course = Course::where('id', $course)
        ->orWhere('code', $course)
        ->orWhere('slug', $course)
        ->firstOrFail();



        $cohort = Cohort::where('course_id', $course->id)
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->first();

        abort_unless($cohort, 404, 'No hay cohorte activa para este curso.');

        $data = $request->validate([
            'name' => ['required','string','min:3','max:120'],
            'dni' => ['required','string','min:6','max:16'],
            'email' => ['required','email','max:190'],
            'phone_whatsapp' => ['required','string','min:8','max:30'],
        ]);

        $dni = preg_replace('/\D+/', '', $data['dni']);
        $wa  = preg_replace('/\D+/', '', $data['phone_whatsapp']);

        // 1) usuario por DNI
        $user = User::where('dni', $dni)->first();

        if ($user) {
            // Si existe y el mail no coincide, evitamos pisar (para no mezclar personas)
            if (!empty($user->email) && strtolower($user->email) !== strtolower($data['email'])) {
                return back()->withErrors([
                    'email' => 'Ese DNI ya existe con otro email. Contactá a administración.'
                ])->withInput();
            }
        } else {
            $user = new User();
            $user->dni = $dni;
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone_whatsapp = $wa;
        $user->account_state = $user->account_state ?? 'pending'; // si usás este campo
        $user->save();

        // 2) enrollment preinscripto
        $enr = Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'cohort_id' => $cohort->id],
            ['status' => 'preinscripto']
        );

        if (empty($enr->public_token)) {
            $enr->public_token = Str::random(48);
        }
        // si ya está inscripto, no lo retrocedas
if (!in_array($enr->status, ['inscripto','baja'], true)) {
    $enr->status = 'preinscripto';
}
$enr->save();


   // 3) email automático (simple)
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
    \Log::warning('Mail preinscripción falló', [
        'user_id' => $user->id ?? null,
        'enrollment_id' => $enr->id ?? null,
        'error' => $e->getMessage(),
    ]);
}


        return redirect()->route('public.receipt.show', ['token' => $enr->public_token])
            ->with('ok', 'Preinscripción registrada. Te enviamos un email con el link para subir comprobante.');
    }

    public function showReceipt(string $token)
    {
        $enr = Enrollment::where('public_token', $token)->firstOrFail();
        $enr->load(['user','cohort','cohort.course']);

        return view('public.receipt_upload', ['enr' => $enr]);
    }

    public function storeReceipt(Request $request, string $token)
    {
        $enr = Enrollment::where('public_token', $token)->firstOrFail();
        $enr->load(['user','cohort','cohort.course']);

        $data = $request->validate([
            'receipt' => ['required','file','mimes:pdf,jpg,jpeg,png','max:8192'], // 8MB
        ]);

        $file = $data['receipt'];

        $path = $file->storeAs(
            'private/receipts/'.date('Y'),
            'enr_'.$enr->id.'_'.time().'.'.$file->getClientOriginalExtension()
        );

        $enr->receipt_path = $path;
        $enr->receipt_original_name = $file->getClientOriginalName();
        $enr->receipt_uploaded_at = now();
        
        if ($enr->status === 'preinscripto') {
    $enr->status = 'pendiente_pago';
}

        $enr->save();

        // Aviso simple al alumno
        Mail::raw(
            "¡Listo {$enr->user->name}!\n\nRecibimos tu comprobante para el curso {$enr->cohort->course->title}.\nEn breve administración lo verificará y te confirmaremos la inscripción.\n\nInstituto Coincidir",
            function ($m) use ($enr) {
                $m->to($enr->user->email)->subject("Comprobante recibido");
            }
        );

        return back()->with('ok', 'Comprobante subido. En breve se verificará tu inscripción.');
    }
}
