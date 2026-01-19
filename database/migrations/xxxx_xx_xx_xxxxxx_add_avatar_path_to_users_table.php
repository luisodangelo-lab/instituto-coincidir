<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path', 255)->nullable()->after('phone_whatsapp');
            // opcional: index si luego listás mucho por esto (no suele ser necesario)
            // $table->index('avatar_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // si agregaste index, quitás primero el index
            // $table->dropIndex(['avatar_path']);
            $table->dropColumn('avatar_path');
        });
    }
};
