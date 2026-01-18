<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otp_challenges', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->string('purpose', 30); // first_access | password_reset | change_whatsapp
    $table->string('code_hash', 255);
    $table->timestamp('expires_at');

    $table->unsignedTinyInteger('attempt_count')->default(0);
    $table->unsignedTinyInteger('resend_count')->default(0);

    $table->timestamp('used_at')->nullable();
    $table->timestamp('invalidated_at')->nullable();

    $table->string('created_ip', 45)->nullable();

    $table->timestamps();

    $table->index(['user_id', 'purpose']);
    $table->index(['expires_at']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('otp_challenges');

    }
};
