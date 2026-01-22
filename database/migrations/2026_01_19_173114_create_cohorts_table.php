<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cohorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->decimal('price_total', 12, 2)->default(0);
            $table->unsignedInteger('installments_count')->default(1);
            $table->unsignedInteger('installment_due_day')->default(10);

            $table->boolean('enrollment_open')->default(true);
            $table->unsignedInteger('max_seats')->nullable();

            $table->timestamps();

            $table->unique(['course_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cohorts');
    }
};
