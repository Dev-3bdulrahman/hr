<?php

namespace Dev3bdulrahman\Hr\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Employee extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $table = 'hr_employees';

    protected $fillable = [
        'company_id',
        'branch_id',
        'user_id',
        'department_id',
        'designation_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(EmploymentContract::class, 'employee_id');
    }

    public function activeContract(): HasOne
    {
        return $this->hasOne(EmploymentContract::class, 'employee_id')
            ->where('status', 'active');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }
}
