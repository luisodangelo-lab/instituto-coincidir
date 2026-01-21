<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cohorts', function (Blueprint $table) {
            if (!Schema::hasColumn('cohorts', 'label')) {
                $table->string('label')->nullable();
                $table->index('label');
            }

            if (!Schema::hasColumn('cohorts', 'enrollment_open_at')) {
                $table->timestamp('enrollment_open_at')->nullable();
                $table->index('enrollment_open_at');
            }
            if (!Schema::hasColumn('cohorts', 'enrollment_close_at')) {
                $table->timestamp('enrollment_close_at')->nullable();
                $table->index('enrollment_close_at');
            }

            if (!Schema::hasColumn('cohorts', 'capacity')) {
                $table->unsignedInteger('capacity')->nullable();
            }

            if (!Schema::hasColumn('cohorts', 'price_ars')) {
                $table->decimal('price_ars', 12, 2)->nullable();
            }

            if (!Schema::hasColumn('cohorts', 'installment_frequency')) {
                $table->string('installment_frequency', 20)->default('one_time'); // one_time|monthly
            }
            if (!Schema::hasColumn('cohorts', 'first_due_date')) {
                $table->date('first_due_date')->nullable();
            }

            if (!Schema::hasColumn('cohorts', 'billing_mode')) {
                $table->string('billing_mode', 20)->default('full'); // full|periodic
            }
            if (!Schema::hasColumn('cohorts', 'period_months')) {
                $table->unsignedTinyInteger('period_months')->nullable(); // 6 o 12
            }
            if (!Schema::hasColumn('cohorts', 'generate_horizon_months')) {
                $table->unsignedTinyInteger('generate_horizon_months')->nullable();
            }

            if (!Schema::hasColumn('cohorts', 'is_public')) {
                $table->boolean('is_public')->default(true);
                $table->index('is_public');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cohorts', function (Blueprint $table) {
            foreach ([
                'label',
                'enrollment_open_at','enrollment_close_at',
                'capacity','price_ars',
                'installment_frequency','first_due_date',
                'billing_mode','period_months','generate_horizon_months',
                'is_public'
            ] as $col) {
                if (Schema::hasColumn('cohorts', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
