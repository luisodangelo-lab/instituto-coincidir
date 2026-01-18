<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 10)->nullable()->unique()->after('id');
            $table->string('phone_whatsapp', 20)->nullable()->after('email');
            $table->timestamp('phone_whatsapp_verified_at')->nullable()->after('phone_whatsapp');
            $table->string('account_state', 30)->default('pending_activation')->after('remember_token');
            $table->string('role', 30)->default('alumno')->after('account_state');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['dni']);
            $table->dropColumn([
                'dni',
                'phone_whatsapp',
                'phone_whatsapp_verified_at',
                'account_state',
                'role',
            ]);
        });
    }
};
