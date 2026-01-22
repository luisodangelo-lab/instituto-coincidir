<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('number'); // 1..N
            $table->date('due_date')->nullable();

            $table->decimal('amount_due', 12, 2)->default(0);
            $table->decimal('amount_paid', 12, 2)->default(0);

            $table->string('status', 20)->default('unpaid'); // unpaid|partial|paid

            $table->timestamps();

            $table->unique(['enrollment_id', 'number']);
            $table->index(['enrollment_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
