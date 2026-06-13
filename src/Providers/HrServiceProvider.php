<?php

namespace Dev3bdulrahman\Hr\Providers;

use Illuminate\Support\ServiceProvider;
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

        // Register Livewire Components
        Livewire::component('hr-employees-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Employees\Index::class);
        Livewire::component('hr-attendance-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Attendance\Index::class);
        Livewire::component('hr-leaves-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Leaves\Index::class);
        Livewire::component('hr-payroll-index', \Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Payroll\Index::class);
    }
}
