<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('installment_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('amount_applied', 12, 2)->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['payment_id']);
            $table->index(['installment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};
