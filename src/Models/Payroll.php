<?php

namespace Dev3bdulrahman\Hr\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $table = 'hr_payrolls';

    protected $fillable = [
        'company_id',
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'status',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:4',
        'allowances' => 'decimal:4',
        'deductions' => 'decimal:4',
        'net_salary' => 'decimal:4',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
