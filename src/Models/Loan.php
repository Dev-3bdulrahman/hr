<?php

namespace Dev3bdulrahman\Hr\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use BelongsToCompany;

    protected $table = 'hr_loans';

    protected $fillable = [
        'company_id',
        'employee_id',
        'amount',
        'paid_amount',
        'monthly_deduction',
        'status',
        'start_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'paid_amount' => 'decimal:4',
        'monthly_deduction' => 'decimal:4',
        'start_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
