<?php

namespace Dev3bdulrahman\Hr\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $table = 'hr_leave_types';

    protected $fillable = [
        'company_id',
        'name',
        'max_days',
    ];

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'leave_type_id');
    }
}
