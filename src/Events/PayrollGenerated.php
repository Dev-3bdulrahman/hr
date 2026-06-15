<?php

namespace Dev3bdulrahman\Hr\Events;

use Dev3bdulrahman\Hr\Models\Payroll;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PayrollGenerated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Payroll $payroll,
        public int $userId,
        public int $companyId,
    ) {}
}
