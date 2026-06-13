<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'role:super-admin|developer|admin|employee', 'license'])
    ->prefix('admin/hr')
    ->group(function () {
        Route::get('/employees', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Employees\Index::class)->name('admin.hr.employees.index');
        Route::get('/departments', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Departments\Index::class)->name('admin.hr.departments.index');
        Route::get('/designations', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Designations\Index::class)->name('admin.hr.designations.index');
        Route::get('/attendance', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Attendance\Index::class)->name('admin.hr.attendance.index');
        Route::get('/leaves', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Leaves\Index::class)->name('admin.hr.leaves.index');
        Route::get('/payroll', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Payroll\Index::class)->name('admin.hr.payroll.index');
    });

