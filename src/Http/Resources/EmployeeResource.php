<?php

namespace Dev3bdulrahman\Hr\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
            'hire_date' => $this->hire_date ? $this->hire_date->toDateString() : null,
            'status' => $this->status,
            'department' => $this->department ? $this->department->name : null,
            'designation' => $this->designation ? $this->designation->name : null,
            'salary' => $this->activeContract ? (float)$this->activeContract->salary : null,
        ];
    }
}
