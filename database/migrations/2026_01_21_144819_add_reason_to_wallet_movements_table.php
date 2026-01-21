<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('wallet_movements', function (Blueprint $table) {
            // si querés evitar choque si ya existe:
            if (!Schema::hasColumn('wallet_movements', 'reason')) {
                $table->string('reason', 32)->default('manual');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wallet_movements', function (Blueprint $table) {
            if (Schema::hasColumn('wallet_movements', 'reason')) {
                $table->dropColumn('reason');
            }
        });
    }
};
