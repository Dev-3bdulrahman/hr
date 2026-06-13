<?php

namespace Dev3bdulrahman\Hr\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee' => $this->employee ? $this->employee->fullName : null,
            'month' => $this->month,
            'year' => $this->year,
            'basic_salary' => (float)$this->basic_salary,
            'allowances' => (float)$this->allowances,
            'deductions' => (float)$this->deductions,
            'net_salary' => (float)$this->net_salary,
            'status' => $this->status,
        ];
    }
}
