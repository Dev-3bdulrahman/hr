<?php

namespace Dev3bdulrahman\Hr\Policies;

use App\Models\User;
use Dev3bdulrahman\Hr\Models\Employee;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('hr.employees.view');
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->can('hr.employees.view') && $employee->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('hr.employees.create');
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->can('hr.employees.update') && $employee->company_id === $user->company_id;
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->can('hr.employees.delete') && $employee->company_id === $user->company_id;
    }
}
