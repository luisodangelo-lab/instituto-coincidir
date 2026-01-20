<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // positivo = crédito, negativo = débito
            $table->decimal('amount', 12, 2);

            // Para auditoría/conciliación:
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->nullOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('notes', 255)->nullable();

            // fecha contable del movimiento (cuando impacta)
            $table->timestamp('accounting_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'accounting_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_movements');
    }
};
