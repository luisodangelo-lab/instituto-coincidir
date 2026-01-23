<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class AdminReset extends Command
{
    protected $signature = 'admin:reset
        {--id= : ID del usuario (ej: 1)}
        {--dni= : DNI del usuario}
        {--email= : Email del usuario}
        {--name=Administrador : Nombre si se crea}
        {--password= : Nueva contraseña (obligatoria)}';

    protected $description = 'Setea rol admin y resetea contraseña (o crea el usuario si no existe)';

    public function handle(): int
    {
        $pwd = (string) $this->option('password');
        if ($pwd === '') {
            $this->error('Falta --password=');
            return self::FAILURE;
        }

        $id = $this->option('id');
        $dni = $this->option('dni');
        $email = $this->option('email');
        $name = (string) $this->option('name') ?: 'Administrador';

        $q = User::query();

        $user = null;

        if ($id) {
            $user = User::find($id);
        }

        if (!$user && $dni && Schema::hasColumn('users', 'dni')) {
            $user = User::where('dni', $dni)->first();
        }

        if (!$user && $email && Schema::hasColumn('users', 'email')) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            // Crear usuario nuevo (solo si hay email o dni)
            $user = new User();
            if ($dni && Schema::hasColumn('users', 'dni')) $user->dni = $dni;
            if ($email && Schema::hasColumn('users', 'email')) $user->email = $email;
            if (Schema::hasColumn('users', 'name')) $user->name = $name;

            $this->info('No existía el usuario. Lo estoy creando...');
        } else {
            $this->info('Usuario encontrado. Lo actualizo...');
        }

        // Rol admin
        if (Schema::hasColumn('users', 'role')) {
            $user->role = 'admin';
        }

        // Password (según columna disponible)
        if (Schema::hasColumn('users', 'password')) {
            $user->password = Hash::make($pwd);
        }
        if (Schema::hasColumn('users', 'password_hash')) {
            $user->password_hash = Hash::make($pwd);
        }

        $user->save();

        $this->line('✅ Admin listo:');
        $this->line('ID: ' . $user->id);
        if (Schema::hasColumn('users', 'dni')) $this->line('DNI: ' . ($user->dni ?? '(vacío)'));
        if (Schema::hasColumn('users', 'email')) $this->line('Email: ' . ($user->email ?? '(vacío)'));
        if (Schema::hasColumn('users', 'role')) $this->line('Role: ' . ($user->role ?? '(sin role)'));

        return self::SUCCESS;
    }
}
