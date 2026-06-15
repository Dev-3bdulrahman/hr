<?php

namespace Dev3bdulrahman\Hr\Policies;

use App\Models\User;
use Dev3bdulrahman\Hr\Models\LeaveRequest;

class LeaveRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('hr.leaves.view');
    }

    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->can('hr.leaves.view') && $leaveRequest->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('hr.leaves.create');
    }

    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->can('hr.leaves.update') && $leaveRequest->company_id === $user->company_id;
    }

    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->can('hr.leaves.delete') && $leaveRequest->company_id === $user->company_id;
    }

    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->can('hr.leaves.approve') && $leaveRequest->company_id === $user->company_id;
    }
}
