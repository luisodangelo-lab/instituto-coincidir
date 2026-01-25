<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Agregar columnas solo si no existen
            if (!Schema::hasColumn('enrollments', 'receipt_path')) {
                $table->string('receipt_path')->nullable();
            }
            if (!Schema::hasColumn('enrollments', 'receipt_original_name')) {
                $table->string('receipt_original_name')->nullable();
            }
            if (!Schema::hasColumn('enrollments', 'receipt_uploaded_at')) {
                $table->timestamp('receipt_uploaded_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Borrar columnas solo si existen
            $drops = [];

            if (Schema::hasColumn('enrollments', 'receipt_path')) {
                $drops[] = 'receipt_path';
            }
            if (Schema::hasColumn('enrollments', 'receipt_original_name')) {
                $drops[] = 'receipt_original_name';
            }
            if (Schema::hasColumn('enrollments', 'receipt_uploaded_at')) {
                $drops[] = 'receipt_uploaded_at';
            }

            if (!empty($drops)) {
                $table->dropColumn($drops);
            }
        });
    }
};

