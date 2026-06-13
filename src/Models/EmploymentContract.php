<?php

namespace Dev3bdulrahman\Hr\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentContract extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $table = 'hr_contracts';

    protected $fillable = [
        'company_id',
        'employee_id',
        'contract_number',
        'start_date',
        'end_date',
        'salary',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'salary' => 'decimal:4',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
