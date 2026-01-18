<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DniLoginController extends Controller
{
    public function show()
    {
        return view('auth.login_dni');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'dni' => ['required', 'string', 'min:6', 'max:10'],
            'password' => ['required', 'string'],
        ]);

        $dni = preg_replace('/\D+/', '', $data['dni']);

        $user = User::where('dni', $dni)->first();

        if (!$user || !$user->password || !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['dni' => 'DNI o contraseña inválidos.'])->withInput();
        }

        if (($user->account_state ?? 'active') !== 'active') {
            return back()->withErrors(['dni' => 'Tu cuenta no está activa. Contactá a administración.'])->withInput();
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}
