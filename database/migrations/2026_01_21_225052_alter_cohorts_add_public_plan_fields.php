<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterCohortsAddPublicPlanFields extends Migration
{
    public function up(): void
    {
        Schema::table('cohorts', function (Blueprint $table) {

            // Tu tabla YA tiene: name, price_total, installments_count, installment_due_day,
            // enrollment_open, max_seats, start_date, end_date.
            // Acá agregamos SOLO lo que falta.

            if (!Schema::hasColumn('cohorts', 'installment_frequency')) {
                $table->string('installment_frequency', 20)->default('monthly'); // monthly|one_time
            }

            if (!Schema::hasColumn('cohorts', 'first_due_date')) {
                $table->date('first_due_date')->nullable(); // opcional
            }

            // Para tecnicaturas/carreras largas
            if (!Schema::hasColumn('cohorts', 'billing_mode')) {
                $table->string('billing_mode', 20)->default('full'); // full|periodic
            }
            if (!Schema::hasColumn('cohorts', 'period_months')) {
                $table->unsignedTinyInteger('period_months')->nullable(); // 6 o 12
            }
            if (!Schema::hasColumn('cohorts', 'generate_horizon_months')) {
                $table->unsignedTinyInteger('generate_horizon_months')->nullable(); // ej 12
            }

            // Ventana fina de inscripción (opcional; además de enrollment_open boolean)
            if (!Schema::hasColumn('cohorts', 'enrollment_open_at')) {
                $table->timestamp('enrollment_open_at')->nullable();
            }
            if (!Schema::hasColumn('cohorts', 'enrollment_close_at')) {
                $table->timestamp('enrollment_close_at')->nullable();
            }
        });

        // Índices seguros para SQLite
        DB::statement("CREATE INDEX IF NOT EXISTS idx_cohorts_billing_mode ON cohorts(billing_mode)");
    }

    public function down(): void
    {
        // En SQLite, dropColumn puede complicar (reconstrucción de tabla).
        // Para evitar errores, lo dejamos vacío.
    }
}
