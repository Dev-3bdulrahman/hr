<?php

namespace Dev3bdulrahman\Hr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryComponent extends Model
{
    protected $table = 'hr_salary_components';

    protected $fillable = [
        'salary_structure_id',
        'name',
        'type',
        'amount',
        'is_fixed',
        'percentage',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'is_fixed' => 'boolean',
        'percentage' => 'decimal:2',
    ];

    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }
}
