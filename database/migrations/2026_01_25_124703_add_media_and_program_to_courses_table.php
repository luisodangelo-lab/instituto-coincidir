<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'axes')) $table->text('axes')->nullable();
            if (!Schema::hasColumn('courses', 'contents')) $table->text('contents')->nullable();
            if (!Schema::hasColumn('courses', 'cover_image_path')) $table->string('cover_image_path')->nullable();
            if (!Schema::hasColumn('courses', 'brochure_pdf_path')) $table->string('brochure_pdf_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'axes')) $table->dropColumn('axes');
            if (Schema::hasColumn('courses', 'contents')) $table->dropColumn('contents');
            if (Schema::hasColumn('courses', 'cover_image_path')) $table->dropColumn('cover_image_path');
            if (Schema::hasColumn('courses', 'brochure_pdf_path')) $table->dropColumn('brochure_pdf_path');
        });
    }
};
