<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Hr\Http\Controllers\Api\EmployeeApiController;
use Dev3bdulrahman\Hr\Http\Controllers\Api\AttendanceApiController;
use Dev3bdulrahman\Hr\Http\Controllers\Api\LeaveApiController;
use Dev3bdulrahman\Hr\Http\Controllers\Api\PayrollApiController;

Route::prefix('api/v1/hr')->middleware(['auth:sanctum', 'throttle:60,1', 'api.tenant'])->group(function () {
    // Employees
    Route::get('employees', [EmployeeApiController::class, 'index'])->name('api.v1.hr.employees.index');
    Route::post('employees', [EmployeeApiController::class, 'store'])->name('api.v1.hr.employees.store');
    Route::get('employees/{employee}', [EmployeeApiController::class, 'show'])->name('api.v1.hr.employees.show');
    Route::put('employees/{employee}', [EmployeeApiController::class, 'update'])->name('api.v1.hr.employees.update');
    Route::delete('employees/{employee}', [EmployeeApiController::class, 'destroy'])->name('api.v1.hr.employees.destroy');

    // Attendance
    Route::get('attendance', [AttendanceApiController::class, 'index'])->name('api.v1.hr.attendance.index');
    Route::post('attendance', [AttendanceApiController::class, 'store'])->name('api.v1.hr.attendance.store');
    Route::get('attendance/{attendance}', [AttendanceApiController::class, 'show'])->name('api.v1.hr.attendance.show');
    Route::delete('attendance/{attendance}', [AttendanceApiController::class, 'destroy'])->name('api.v1.hr.attendance.destroy');

    // Leaves
    Route::get('leaves', [LeaveApiController::class, 'index'])->name('api.v1.hr.leaves.index');
    Route::post('leaves', [LeaveApiController::class, 'store'])->name('api.v1.hr.leaves.store');
    Route::get('leaves/{leaveRequest}', [LeaveApiController::class, 'show'])->name('api.v1.hr.leaves.show');
    Route::post('leaves/{leaveRequest}/approve', [LeaveApiController::class, 'approve'])->name('api.v1.hr.leaves.approve');
    Route::delete('leaves/{leaveRequest}', [LeaveApiController::class, 'destroy'])->name('api.v1.hr.leaves.destroy');

    // Payrolls
    Route::get('payrolls', [PayrollApiController::class, 'index'])->name('api.v1.hr.payrolls.index');
    Route::post('payrolls', [PayrollApiController::class, 'store'])->name('api.v1.hr.payrolls.store');
    Route::get('payrolls/{payroll}', [PayrollApiController::class, 'show'])->name('api.v1.hr.payrolls.show');
    Route::post('payrolls/{payroll}/approve', [PayrollApiController::class, 'approve'])->name('api.v1.hr.payrolls.approve');
    Route::delete('payrolls/{payroll}', [PayrollApiController::class, 'destroy'])->name('api.v1.hr.payrolls.destroy');
});
