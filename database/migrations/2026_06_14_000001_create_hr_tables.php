<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Departments
        Schema::create('hr_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Designations
        Schema::create('hr_designations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Employees
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->unsignedBigInteger('designation_id')->nullable()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('hire_date');
            $table->string('status')->default('active'); // active, inactive, suspended
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Contracts
        Schema::create('hr_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->string('contract_number')->unique();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('salary', 15, 4);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Attendances
        Schema::create('hr_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->date('date')->index();
            $table->time('check_in');
            $table->time('check_out')->nullable();
            $table->string('status')->default('present'); // present, late, absent
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employee_id', 'date']);
        });

        // 6. Leave Types
        Schema::create('hr_leave_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('name');
            $table->integer('max_days');
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Leave Requests
        Schema::create('hr_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->unsignedBigInteger('leave_type_id')->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. Payrolls
        Schema::create('hr_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->integer('month');
            $table->integer('year');
            $table->decimal('basic_salary', 15, 4);
            $table->decimal('allowances', 15, 4)->default(0);
            $table->decimal('deductions', 15, 4)->default(0);
            $table->decimal('net_salary', 15, 4);
            $table->string('status')->default('draft'); // draft, paid
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employee_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_payrolls');
        Schema::dropIfExists('hr_leave_requests');
        Schema::dropIfExists('hr_leave_types');
        Schema::dropIfExists('hr_attendances');
        Schema::dropIfExists('hr_contracts');
        Schema::dropIfExists('hr_employees');
        Schema::dropIfExists('hr_designations');
        Schema::dropIfExists('hr_departments');
    }
};
