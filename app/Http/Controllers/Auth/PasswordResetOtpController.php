<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpCodeMail;

class PasswordResetOtpController extends Controller
{
    public function show()
    {
        return view('auth.reset_dni');
    }

    public function sendOtp(Request $request, OtpService $otp)
    {
        $data = $request->validate([
            'dni' => ['required', 'string', 'max:16'],
        ]);

        $dni = preg_replace('/\D+/', '', $data['dni']);
        $user = User::where('dni', $dni)->first();

        // Validaciones mínimas para recuperar por email
        if (!$user || empty($user->email)) {
            return back()->withErrors([
                'dni' => 'No se pudo iniciar la recuperación. Verificá el DNI o contactá a administración.',
            ]);
        }

        // Si no está activo, que use Primer acceso
        if (($user->account_state ?? 'active') !== 'active') {
            return redirect()->route('first_access.show')
                ->with('info', 'Tu cuenta aún no está activa. Hacé Primer acceso.');
        }

        // 1) Crear challenge
        $res = $otp->createChallenge($user->id, 'password_reset', $request->ip());

        // 2) Enviar mail con el código
        try {
            Mail::to($user->email)->send(
                new OtpCodeMail($res['code_plain'], 'password_reset', $user->name ?? '')
            );
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors([
                'dni' => 'No pudimos enviar el código por email. Intentá de nuevo.',
            ]);
        }

        // 3) Guardar en sesión para el siguiente paso
        session()->forget(['pr_user_id','pr_challenge_id','pr_verified','pr_dev_code']);
        session([
            'pr_user_id' => $user->id,
            'pr_challenge_id' => $res['challenge']->id, // ✅ consistente con tu OtpService
        ]);

        // Solo para pruebas en local
        if (config('otp.show_dev_code') && app()->environment('local')) {
            session(['pr_dev_code' => $res['code_plain']]);
        }

        return redirect()->route('reset.verify')
            ->with('ok', 'Te enviamos un código a tu email.');
    }

    public function showVerify()
    {
        if (!session('pr_user_id') || !session('pr_challenge_id')) {
            return redirect()->route('reset.show');
        }
        return view('auth.reset_verify');
    }

    public function verify(Request $request, OtpService $otp)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $challengeId = (int) session('pr_challenge_id');
        if (!$challengeId) {
            return redirect()->route('reset.show');
        }

        $ok = $otp->verify($challengeId, $data['code'], $request->ip());
        if (!$ok) {
            return back()->withErrors(['code' => 'Código inválido o vencido.']);
        }

        session(['pr_verified' => true]);
        return redirect()->route('reset.password');
    }

    public function showPassword()
    {
        if (!session('pr_user_id') || !session('pr_verified')) {
            return redirect()->route('reset.show');
        }
        return view('auth.reset_password');
    }

    public function setPassword(Request $request)
    {
        if (!session('pr_user_id') || !session('pr_verified')) {
            return redirect()->route('reset.show');
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail((int) session('pr_user_id'));
        $user->password = Hash::make($data['password']);
        $user->save();

        Auth::login($user);

        session()->forget(['pr_user_id','pr_challenge_id','pr_verified','pr_dev_code']);

        return redirect()->route('dashboard');
    }
}
