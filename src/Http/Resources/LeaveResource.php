<?php

namespace Dev3bdulrahman\Hr\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee' => $this->employee ? $this->employee->fullName : null,
            'leave_type' => $this->leaveType ? $this->leaveType->name : null,
            'start_date' => $this->start_date ? $this->start_date->toDateString() : null,
            'end_date' => $this->end_date ? $this->end_date->toDateString() : null,
            'status' => $this->status,
            'reason' => $this->reason,
            'approved_by' => $this->approver ? $this->approver->name : null,
        ];
    }
}
