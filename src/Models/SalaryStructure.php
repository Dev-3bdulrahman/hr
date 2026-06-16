<?php

namespace Dev3bdulrahman\Hr\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryStructure extends Model
{
    use BelongsToCompany;

    protected $table = 'hr_salary_structures';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function components(): HasMany
    {
        return $this->hasMany(SalaryComponent::class, 'salary_structure_id');
    }
}
