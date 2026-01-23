<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordResetOtpController extends Controller
{
    public function show()
    {
        return view('auth.reset_dni');
    }

    public function sendOtp(Request $request, OtpService $otp, WhatsAppService $wa)
    {
        $data = $request->validate([
            'dni' => ['required', 'string', 'min:6', 'max:10'],
        ]);

        $dni = preg_replace('/\D+/', '', $data['dni']);
        $user = User::where('dni', $dni)->first();

        if (!$user || empty($user->phone_whatsapp)) {
            return back()->withErrors(['dni' => 'No se pudo iniciar la recuperación. Verificá el DNI o contactá a administración.']);
        }

        // Si no está activo, que use Primer acceso
        if (($user->account_state ?? 'active') !== 'active') {
            return redirect('/first-access')->with('info', 'Tu cuenta aún no está activa. Hacé Primer acceso.');
        }

        $res = $otp->createChallenge($user->id, 'password_reset', $request->ip());
        $wa->sendOtp($user->phone_whatsapp, $otp['code'], 'password_reset');


        session([
            'pr_user_id' => $user->id,
            'pr_challenge_id' => $res['challenge']->id,
        ]);

        if (config('otp.show_dev_code') && app()->environment('local')) {
            session(['pr_dev_code' => $res['code_plain']]);
        }

        return redirect()->route('reset.verify');
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
        if (!$challengeId) return redirect()->route('reset.show');

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
