<?php

namespace Dev3bdulrahman\Hr\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    use BelongsToCompany;

    protected $table = 'hr_payroll_items';

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'type',
        'name',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
