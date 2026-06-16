<?php

namespace Dev3bdulrahman\Hr\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penalty extends Model
{
    use BelongsToCompany;

    protected $table = 'hr_penalties';

    protected $fillable = [
        'company_id',
        'employee_id',
        'type',
        'amount',
        'reason',
        'date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
