<?php

namespace Dev3bdulrahman\Hr\Policies;

use App\Models\User;
use Dev3bdulrahman\Hr\Models\Payroll;

class PayrollPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('hr.payroll.view');
    }

    public function view(User $user, Payroll $payroll): bool
    {
        return $user->can('hr.payroll.view') && $payroll->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('hr.payroll.create');
    }

    public function update(User $user, Payroll $payroll): bool
    {
        return $user->can('hr.payroll.update') && $payroll->company_id === $user->company_id;
    }

    public function delete(User $user, Payroll $payroll): bool
    {
        return $user->can('hr.payroll.delete') && $payroll->company_id === $user->company_id;
    }

    public function approve(User $user, Payroll $payroll): bool
    {
        return $user->can('hr.payroll.approve') && $payroll->company_id === $user->company_id;
    }
}
