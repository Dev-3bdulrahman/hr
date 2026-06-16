<?php

namespace Dev3bdulrahman\Hr\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends Model
{
    use BelongsToCompany;

    protected $table = 'hr_employee_documents';

    protected $fillable = [
        'company_id',
        'employee_id',
        'title',
        'file_path',
        'type',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
