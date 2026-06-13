<?php

namespace Dev3bdulrahman\Hr\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee' => $this->employee ? $this->employee->fullName : null,
            'date' => $this->date ? $this->date->toDateString() : null,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'status' => $this->status,
        ];
    }
}
