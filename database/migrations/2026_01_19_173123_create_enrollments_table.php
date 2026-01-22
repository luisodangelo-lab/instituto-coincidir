<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cohort_id')->constrained()->cascadeOnDelete();

            // Para el flujo de preinscripción
            $table->string('status', 30)->default('preinscripto'); // preinscripto|inscripto|cursando|aprobado|baja
            $table->dateTime('enrolled_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'cohort_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
