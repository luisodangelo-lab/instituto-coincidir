<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class FirstAccessController extends Controller
{
    public function show()
    {
        return view('auth.first_access_dni');
    }

    public function sendOtp(Request $request, OtpService $otp)
{
    $data = $request->validate([
        'dni' => ['required','string','max:16'],
    ]);

    $dni = preg_replace('/\D+/', '', $data['dni']);
    $user = User::where('dni', $dni)->first();

    if (!$user) {
        return back()->with('error', 'No encontramos un usuario con ese DNI.');
    }

    if (empty($user->email)) {
        return back()->with('error', 'Tu usuario no tiene email cargado. Contactá a administración.');
    }

    // 1) Crear challenge
    $res = $otp->createChallenge($user->id, 'first_access', $request->ip());

    // 2) Enviar mail con el código
    try {
        Mail::to($user->email)->send(
            new OtpCodeMail($res['code_plain'], 'first_access', $user->name ?? '')
        );
    } catch (\Throwable $e) {
        report($e);
        return back()->withErrors(['dni' => 'No pudimos enviar el código por email. Intentá de nuevo.']);
    }

    // 3) Guardar en sesión para el siguiente paso
    session()->forget(['fa_user_id','fa_challenge_id','fa_verified','fa_dev_code']);
    session([
        'fa_user_id' => $user->id,
        'fa_challenge_id' => $res['challenge']->id, // ✅ ESTE ES EL PUNTO CLAVE
    ]);

    if (app()->environment('local')) {
        session(['fa_dev_code' => $res['code_plain']]);
    }

    return redirect()->route('first_access.verify')
        ->with('ok', 'Te enviamos un código a tu email.');
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

    // ✅ Si el primer acceso fue por EMAIL, marcamos email verificado (si existe la columna)
    if (Schema::hasColumn('users', 'email_verified_at') && empty($user->email_verified_at)) {
        $user->email_verified_at = now();
    }

    // ❌ NO marcar WhatsApp verificado acá (a menos que realmente lo verifiques por OTP WhatsApp)
    // if (Schema::hasColumn('users', 'phone_whatsapp_verified_at') && empty($user->phone_whatsapp_verified_at)) {
    //     $user->phone_whatsapp_verified_at = now();
    // }

    $user->save();

    Auth::login($user);

    session()->forget([
        'fa_user_id', 'fa_challenge_id', 'fa_verified', 'fa_dev_code'
    ]);

    return redirect('/dashboard')->with('ok', 'Listo. Tu contraseña fue creada y tu cuenta quedó activa.');
}

}
