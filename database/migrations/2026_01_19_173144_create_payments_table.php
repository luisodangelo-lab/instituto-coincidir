<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();

            $table->string('type', 20)->default('payment');         // payment|refund
            $table->string('provider', 30)->default('manual');      // manual|mercadopago(futuro)
            $table->string('method', 30)->default('transferencia'); // transferencia|efectivo|otro|mercadopago

            $table->decimal('amount', 12, 2)->default(0);

            $table->string('status', 30)->default('pending_review'); // pending_review|approved|rejected
            $table->string('reference')->nullable();

            $table->string('receipt_path')->nullable();
            $table->dateTime('paid_at')->nullable();

            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();

            $table->foreignId('refunded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('refunded_at')->nullable();

            $table->foreignId('refund_of_payment_id')->nullable()->constrained('payments')->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['status']);
            $table->index(['user_id']);
            $table->index(['enrollment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
