<?php

namespace Dev3bdulrahman\Hr\Policies;

use App\Models\User;
use Dev3bdulrahman\Hr\Models\Attendance;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('hr.attendance.view');
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->can('hr.attendance.view') && $attendance->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('hr.attendance.create');
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->can('hr.attendance.update') && $attendance->company_id === $user->company_id;
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->can('hr.attendance.delete') && $attendance->company_id === $user->company_id;
    }
}
