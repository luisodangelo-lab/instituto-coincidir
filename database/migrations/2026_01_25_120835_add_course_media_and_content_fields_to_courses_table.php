<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses','disposition_number')) {
                $table->string('disposition_number', 100)->nullable();
            }
            if (!Schema::hasColumn('courses','axes')) {
                $table->longText('axes')->nullable();
            }
            if (!Schema::hasColumn('courses','contents')) {
                $table->longText('contents')->nullable();
            }

            if (!Schema::hasColumn('courses','cover_image_path')) {
                $table->string('cover_image_path')->nullable();
            }
            if (!Schema::hasColumn('courses','cover_image_original_name')) {
                $table->string('cover_image_original_name')->nullable();
            }

            if (!Schema::hasColumn('courses','brochure_pdf_path')) {
                $table->string('brochure_pdf_path')->nullable();
            }
            if (!Schema::hasColumn('courses','brochure_pdf_original_name')) {
                $table->string('brochure_pdf_original_name')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $cols = [
                'disposition_number','axes','contents',
                'cover_image_path','cover_image_original_name',
                'brochure_pdf_path','brochure_pdf_original_name',
            ];
            foreach ($cols as $c) {
                if (Schema::hasColumn('courses', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
