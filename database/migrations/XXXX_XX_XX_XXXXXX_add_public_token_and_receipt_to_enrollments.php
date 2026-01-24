<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'public_token')) {
                $table->string('public_token', 64)->nullable()->unique()->after('id');
            }
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
            if (Schema::hasColumn('enrollments', 'public_token')) $table->dropUnique(['public_token']);
            $table->dropColumn(['public_token','receipt_path','receipt_original_name','receipt_uploaded_at']);
        });
    }
};
