<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {

            if (!Schema::hasColumn('courses', 'expediente_number')) {
                $table->string('expediente_number', 100)->nullable();
            }

            if (!Schema::hasColumn('courses', 'resolution_number')) {
                $table->string('resolution_number', 100)->nullable();
            }

            if (!Schema::hasColumn('courses', 'presentation_date')) {
                $table->date('presentation_date')->nullable();
            }

            if (!Schema::hasColumn('courses', 'ministry_approved')) {
                $table->boolean('ministry_approved')->default(false);
            }

            if (!Schema::hasColumn('courses', 'hours_total')) {
                $table->unsignedInteger('hours_total')->nullable();
            }

            if (!Schema::hasColumn('courses', 'duration_weeks')) {
                $table->unsignedInteger('duration_weeks')->nullable();
            }
        });

        // Índices seguros en SQLite
        DB::statement("CREATE INDEX IF NOT EXISTS idx_courses_expediente ON courses(expediente_number)");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_courses_resolution ON courses(resolution_number)");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_courses_ministry_approved ON courses(ministry_approved)");
    }

    public function down(): void
    {
        // En SQLite, rollback con dropColumn puede requerir reconstrucción de tabla.
        // Por seguridad lo dejamos vacío (en producción igual no solemos hacer rollback).
    }
};
