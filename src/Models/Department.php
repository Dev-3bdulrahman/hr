<?php

namespace Dev3bdulrahman\Hr\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $table = 'hr_departments';

    protected $fillable = [
        'company_id',
        'name',
        'status',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_id');
    }
}
