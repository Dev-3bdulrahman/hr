<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add extra columns to departments
        Schema::table('hr_departments', function (Blueprint $table) {
            if (!Schema::hasColumn('hr_departments', 'code')) {
                $table->string('code', 50)->nullable()->after('name');
            }
            if (!Schema::hasColumn('hr_departments', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('code');
                $table->foreign('parent_id')->references('id')->on('hr_departments')->nullOnDelete();
            }
            if (!Schema::hasColumn('hr_departments', 'manager_id')) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('parent_id');
                $table->foreign('manager_id')->references('id')->on('hr_employees')->nullOnDelete();
            }
        });

        // Add extra columns to designations
        Schema::table('hr_designations', function (Blueprint $table) {
            if (!Schema::hasColumn('hr_designations', 'level')) {
                $table->string('level', 100)->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hr_departments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['code', 'parent_id', 'manager_id']);
        });

        Schema::table('hr_designations', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
