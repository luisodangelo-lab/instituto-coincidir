<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('cohorts', function (Blueprint $table) {
        if (!Schema::hasColumn('cohorts', 'price_ars')) {
            $table->integer('price_ars')->nullable()->after('end_date');
        }
        if (!Schema::hasColumn('cohorts', 'capacity')) {
            $table->integer('capacity')->nullable()->after('price_ars');
        }
        if (!Schema::hasColumn('cohorts', 'notes')) {
            $table->text('notes')->nullable()->after('capacity');
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('cohorts', function (Blueprint $table) {
        $drops = [];
        foreach (['price_ars','capacity','notes'] as $c) {
            if (Schema::hasColumn('cohorts', $c)) $drops[] = $c;
        }
        if ($drops) $table->dropColumn($drops);
    });
}

};
