<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // status y enrolled_at YA existen en tu tabla actual, no los tocamos.

            if (!Schema::hasColumn('enrollments', 'source')) {
                $table->string('source', 20)->default('web'); // web|admin|import
            }

            if (!Schema::hasColumn('enrollments', 'assigned_to_user_id')) {
                $table->unsignedBigInteger('assigned_to_user_id')->nullable();
            }

            if (!Schema::hasColumn('enrollments', 'contacted_at')) {
                $table->timestamp('contacted_at')->nullable();
            }

            if (!Schema::hasColumn('enrollments', 'payment_instructions_sent_at')) {
                $table->timestamp('payment_instructions_sent_at')->nullable();
            }

            if (!Schema::hasColumn('enrollments', 'status_reason')) {
                $table->string('status_reason')->nullable();
            }

            if (!Schema::hasColumn('enrollments', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('enrollments', 'price_snapshot')) {
                $table->decimal('price_snapshot', 12, 2)->nullable();
            }

            if (!Schema::hasColumn('enrollments', 'plan_snapshot')) {
                $table->json('plan_snapshot')->nullable(); // SQLite lo guarda como TEXT
            }
        });
    }

    public function down(): void
    {
        // En SQLite, dropColumn suele requerir reconstrucción de tabla.
        // Para evitar problemas, lo dejamos vacío.
    }
};
