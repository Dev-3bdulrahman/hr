<?php

namespace Dev3bdulrahman\Hr\Events;

use Dev3bdulrahman\Hr\Models\LeaveRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public LeaveRequest $leaveRequest,
        public int $userId,
        public int $companyId,
    ) {}
}
