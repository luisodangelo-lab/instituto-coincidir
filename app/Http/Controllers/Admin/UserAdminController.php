<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PhoneNormalizer;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function create()
    {
        return view('admin.users_create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'dni'  => ['required','string','min:6','max:10','unique:users,dni'],
            'phone_whatsapp' => ['required','string','min:8','max:30'],
            'email' => ['nullable','email','max:190','unique:users,email'],
            'role'  => ['required','string','in:alumno,docente,administrativo,staff_l1,staff_l2,admin'],
        ]);

        $dni = preg_replace('/\D+/', '', $data['dni']);

        $phone = PhoneNormalizer::normalizeArToE164($data['phone_whatsapp']);
        if (!$phone) {
            return back()->withErrors([
                'phone_whatsapp' => 'WhatsApp inválido. Probá con 2804514348 o +5492804514348'
            ])->withInput();
        }

        // Email placeholder si no hay email real
        $email = $data['email'] ?? ("dni{$dni}@local.invalid");

        // Contraseña temporal: el alumno la cambia en Primer acceso
        $tempPass = str()->random(16);

        User::create([
            'name' => $data['name'],
            'dni'  => $dni,
            'email' => $email,
            'phone_whatsapp' => $phone,
            'role' => $data['role'],
            'account_state' => 'pending_activation',
            'password' => bcrypt($tempPass),
        ]);

        return redirect()->route('admin.users.create')
            ->with('ok', "Usuario creado. DNI {$dni}. Debe ingresar por Primer acceso.");
    }
}
