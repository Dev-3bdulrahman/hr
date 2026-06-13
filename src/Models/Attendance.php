<?php

namespace Dev3bdulrahman\Hr\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $table = 'hr_attendances';

    protected $fillable = [
        'company_id',
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
