<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['dni' => '18088247'],
            [
                'name' => 'Admin',
                'email' => 'admin@institutocoincidir.org',
                'password' => Hash::make('cheto2001'),
                'role' => 'admin',
                'account_state' => 'active',
                'phone_whatsapp' => null,
            ]
        );
    }
}
