<?php

namespace Dev3bdulrahman\Hr\Services;

use Dev3bdulrahman\Hr\Models\Department;
use Dev3bdulrahman\Hr\Models\Designation;
use Dev3bdulrahman\Hr\Models\Employee;
use Dev3bdulrahman\Hr\Models\EmploymentContract;
use Dev3bdulrahman\Hr\Models\Attendance;
use Dev3bdulrahman\Hr\Models\LeaveType;
use Dev3bdulrahman\Hr\Models\LeaveRequest;
use Dev3bdulrahman\Hr\Models\Payroll;
use Illuminate\Support\Facades\DB;

class HrService
{
    // ==========================================
    // 1. Employees CRUD
    // ==========================================
    public function createEmployee(array $data, int $companyId): Employee
    {
        return DB::transaction(function () use ($data, $companyId) {
            $employee = Employee::create(array_merge($data, [
                'company_id' => $companyId,
            ]));

            if (isset($data['salary']) && $data['salary'] > 0) {
                EmploymentContract::create([
                    'company_id' => $companyId,
                    'employee_id' => $employee->id,
                    'contract_number' => 'CON-' . time() . '-' . rand(100, 999),
                    'start_date' => $data['hire_date'] ?? now()->toDateString(),
                    'salary' => $data['salary'],
                    'status' => 'active',
                ]);
            }

            return $employee;
        });
    }

    public function updateEmployee(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data) {
            $employee->update($data);

            if (isset($data['salary'])) {
                // Deactivate old contract and create new one if salary changed
                $activeContract = $employee->activeContract;
                if (!$activeContract || $activeContract->salary != $data['salary']) {
                    if ($activeContract) {
                        $activeContract->update(['status' => 'inactive', 'end_date' => now()->toDateString()]);
                    }
                    EmploymentContract::create([
                        'company_id' => $employee->company_id,
                        'employee_id' => $employee->id,
                        'contract_number' => 'CON-' . time() . '-' . rand(100, 999),
                        'start_date' => now()->toDateString(),
                        'salary' => $data['salary'],
                        'status' => 'active',
                    ]);
                }
            }

            return $employee;
        });
    }

    public function toggleEmployeeStatus(Employee $employee): Employee
    {
        $newStatus = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->update(['status' => $newStatus]);
        return $employee;
    }

    public function deleteEmployee(Employee $employee): void
    {
        DB::transaction(function () use ($employee) {
            $employee->contracts()->delete();
            $employee->attendances()->delete();
            $employee->leaveRequests()->delete();
            $employee->payrolls()->delete();
            $employee->delete();
        });
    }

    // ==========================================
    // 2. Attendance Management
    // ==========================================
    public function logAttendance(int $employeeId, string $date, string $checkIn, ?string $checkOut = null, int $companyId): Attendance
    {
        $status = 'present';
        // Determine status based on standard check-in time (e.g. 09:00:00)
        if (strtotime($checkIn) > strtotime('09:00:00')) {
            $status = 'late';
        }

        return Attendance::updateOrCreate(
            ['employee_id' => $employeeId, 'date' => $date],
            [
                'company_id' => $companyId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $status,
            ]
        );
    }

    // ==========================================
    // 3. Leave Requests
    // ==========================================
    public function requestLeave(array $data, int $companyId): LeaveRequest
    {
        return LeaveRequest::create(array_merge($data, [
            'company_id' => $companyId,
            'status' => 'pending',
        ]));
    }

    public function processLeave(LeaveRequest $leaveRequest, string $status, int $userId): LeaveRequest
    {
        $leaveRequest->update([
            'status' => $status,
            'approved_by' => $userId,
        ]);
        return $leaveRequest;
    }

    // ==========================================
    // 4. Payroll Generation
    // ==========================================
    public function generatePayroll(int $companyId, int $month, int $year): void
    {
        DB::transaction(function () use ($companyId, $month, $year) {
            $employees = Employee::where('company_id', $companyId)
                ->where('status', 'active')
                ->get();

            foreach ($employees as $employee) {
                $contract = $employee->activeContract;
                if (!$contract) {
                    continue;
                }

                $basicSalary = $contract->salary;
                
                // Simple calculation rules:
                // Deduct 1/30 of basic salary for each absent day
                $absences = Attendance::where('employee_id', $employee->id)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('status', 'absent')
                    ->count();

                $deductions = ($basicSalary / 30) * $absences;
                $allowances = 0; // Can be expanded in future versions

                // Ensure it's not negative
                $netSalary = max(0, $basicSalary + $allowances - $deductions);

                Payroll::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'month' => $month,
                        'year' => $year,
                    ],
                    [
                        'company_id' => $companyId,
                        'basic_salary' => $basicSalary,
                        'allowances' => $allowances,
                        'deductions' => $deductions,
                        'net_salary' => $netSalary,
                        'status' => 'draft',
                    ]
                );
            }
        });
    }

    public function payPayroll(Payroll $payroll): Payroll
    {
        $payroll->update(['status' => 'paid']);
        return $payroll;
    }
}
