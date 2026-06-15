<?php

namespace Dev3bdulrahman\Hr\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Dev3bdulrahman\Hr\Events\LeaveRequestSubmitted;
use Dev3bdulrahman\Hr\Events\PayrollGenerated;
use Dev3bdulrahman\Hr\Listeners\LogPayrollGenerated;
use Dev3bdulrahman\Hr\Listeners\NotifyLeaveRequestSubmitted;
use Dev3bdulrahman\Hr\Models\Attendance;
use Dev3bdulrahman\Hr\Models\Employee;
use Dev3bdulrahman\Hr\Models\LeaveRequest;
use Dev3bdulrahman\Hr\Models\Payroll;
use Dev3bdulrahman\Hr\Policies\AttendancePolicy;
use Dev3bdulrahman\Hr\Policies\EmployeePolicy;
use Dev3bdulrahman\Hr\Policies\LeaveRequestPolicy;
use Dev3bdulrahman\Hr\Policies\PayrollPolicy;
use Livewire\Livewire;

class HrServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'hr');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../Translations', 'hr');

        // Register Policies
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Payroll::class, PayrollPolicy::class);
        Gate::policy(LeaveRequest::class, LeaveRequestPolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);

        // Register Event Listeners
        Event::listen(LeaveRequestSubmitted::class, NotifyLeaveRequestSubmitted::class);
        Event::listen(PayrollGenerated::class, LogPayrollGenerated::class);

        // Register Livewire Components
        if (class_exists(\Livewire\Livewire::class)) {
            Livewire::component('hr-employees-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Employees\Index::class);
            Livewire::component('hr-departments-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Departments\Index::class);
            Livewire::component('hr-designations-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Designations\Index::class);
            Livewire::component('hr-attendance-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Attendance\Index::class);
            Livewire::component('hr-leaves-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Leaves\Index::class);
            Livewire::component('hr-payroll-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Payroll\Index::class);
        }
    }
}
