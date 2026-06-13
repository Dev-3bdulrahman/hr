<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum', 'license'])
    ->prefix('api/v1/hr')
    ->group(function () {
        Route::get('/employees', [\Dev3bdulrahman\Hr\Http\Controllers\Api\EmployeeApiController::class, 'index']);
        Route::get('/employees/{id}', [\Dev3bdulrahman\Hr\Http\Controllers\Api\EmployeeApiController::class, 'show']);
        Route::post('/attendance/log', [\Dev3bdulrahman\Hr\Http\Controllers\Api\AttendanceApiController::class, 'log']);
        Route::post('/leaves/request', [\Dev3bdulrahman\Hr\Http\Controllers\Api\LeaveApiController::class, 'request']);
        Route::get('/payrolls', [\Dev3bdulrahman\Hr\Http\Controllers\Api\PayrollApiController::class, 'index']);
    });
