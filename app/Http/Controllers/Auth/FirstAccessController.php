<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FirstAccessController extends Controller
{
    public function show()
    {
        return view('auth.first_access_dni');
    }

    public function sendOtp(Request $request, OtpService $otp, WhatsAppService $wa)
    {
        $data = $request->validate([
            'dni' => ['required', 'string', 'min:6', 'max:10'],
        ]);

        $dni = preg_replace('/\D+/', '', $data['dni']);

        $user = User::where('dni', $dni)->first();

        if (!$user) {
            return back()->withErrors(['dni' => 'No encontramos una cuenta con ese DNI.']);
        }

        if (empty($user->phone_whatsapp)) {
            return back()->withErrors(['dni' => 'Tu cuenta no tiene WhatsApp registrado. Contactá a administración.']);
        }

        $res = $otp->createChallenge($user->id, 'first_access', $request->ip());

        // Enviar OTP por WhatsApp (DEV: queda en log)
        $wa->sendOtp($user->phone_whatsapp, $res['code_plain']);

        // Guardar en sesión para el siguiente paso
        session([
            'fa_user_id' => $user->id,
            'fa_challenge_id' => $res['challenge']->id,
        ]);

        // En local, mostramos el código en pantalla SOLO PARA PRUEBAS
        if (config('app.env') === 'local') {
            session(['fa_dev_code' => $res['code_plain']]);
        }

        return redirect()->route('first_access.verify');
    }

    public function showVerify()
    {
        if (!session('fa_user_id') || !session('fa_challenge_id')) {
            return redirect()->route('first_access.show');
        }

        return view('auth.first_access_verify');
    }

    public function verify(Request $request, OtpService $otp)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $challengeId = (int) session('fa_challenge_id');
        if (!$challengeId) {
            return redirect()->route('first_access.show');
        }

        $ok = $otp->verify($challengeId, $data['code'], $request->ip());

        if (!$ok) {
            return back()->withErrors(['code' => 'Código inválido o vencido.']);
        }

        session(['fa_verified' => true]);

        return redirect()->route('first_access.password');
    }

    public function showPassword()
    {
        if (!session('fa_user_id') || !session('fa_verified')) {
            return redirect()->route('first_access.show');
        }

        return view('auth.first_access_password');
    }

    public function setPassword(Request $request)
    {
        if (!session('fa_user_id') || !session('fa_verified')) {
            return redirect()->route('first_access.show');
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail((int) session('fa_user_id'));

        $user->password = Hash::make($data['password']);
        $user->account_state = 'active';
        // si querés marcar verificado al primer acceso:
        $user->phone_whatsapp_verified_at = now();
        $user->save();

        // Loguear y limpiar sesión
        Auth::login($user);

        session()->forget([
            'fa_user_id', 'fa_challenge_id', 'fa_verified', 'fa_dev_code'
        ]);

        return redirect('/dashboard');
    }
}
