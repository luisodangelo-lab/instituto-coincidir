<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'category')) {
                $table->string('category', 80)->nullable()->after('type');
            }
            if (!Schema::hasColumn('courses', 'axes')) {
                $table->longText('axes')->nullable()->after('description');
            }
            if (!Schema::hasColumn('courses', 'contents')) {
                $table->longText('contents')->nullable()->after('axes');
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
            if (Schema::hasColumn('courses', 'brochure_path')) $table->dropColumn('brochure_path');
            if (Schema::hasColumn('courses', 'cover_path')) $table->dropColumn('cover_path');
            if (Schema::hasColumn('courses', 'contents')) $table->dropColumn('contents');
            if (Schema::hasColumn('courses', 'axes')) $table->dropColumn('axes');
            if (Schema::hasColumn('courses', 'category')) $table->dropColumn('category');
        });
    }
};
