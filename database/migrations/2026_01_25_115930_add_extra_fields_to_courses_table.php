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
    Schema::table('courses', function (Blueprint $table) {
        if (!Schema::hasColumn('courses', 'disposition')) {
            $table->string('disposition')->nullable()->after('title');
        }
        if (!Schema::hasColumn('courses', 'description')) {
            $table->text('description')->nullable()->after('disposition');
        }
        if (!Schema::hasColumn('courses', 'axes')) {
            $table->text('axes')->nullable()->after('description');
        }
        if (!Schema::hasColumn('courses', 'contents')) {
            $table->text('contents')->nullable()->after('axes');
        }
        if (!Schema::hasColumn('courses', 'cover_path')) {
            $table->string('cover_path')->nullable()->after('contents');
        }
        if (!Schema::hasColumn('courses', 'brochure_path')) {
            $table->string('brochure_path')->nullable()->after('cover_path');
        }
    });
}


    
public function down(): void
{
    Schema::table('courses', function (Blueprint $table) {
        $drops = [];
        foreach (['disposition','description','axes','contents','cover_path','brochure_path'] as $c) {
            if (Schema::hasColumn('courses', $c)) $drops[] = $c;
        }
        if ($drops) $table->dropColumn($drops);
    });
}



};
