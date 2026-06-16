<?php

namespace Dev3bdulrahman\Hr\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PerformanceReview extends Model
{
    use BelongsToCompany;

    protected $table = 'hr_performance_reviews';

    protected $fillable = [
        'company_id',
        'employee_id',
        'reviewer_id',
        'review_date',
        'score',
        'comments',
        'status',
    ];

    protected $casts = [
        'review_date' => 'date',
        'score' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
