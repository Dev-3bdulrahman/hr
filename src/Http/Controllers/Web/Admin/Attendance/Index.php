<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Attendance;

use Dev3bdulrahman\Hr\Models\Attendance;
use Dev3bdulrahman\Hr\Models\Employee;
use Dev3bdulrahman\Hr\Services\HrService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $date = '';
    public string $check_in = '09:00:00';
    public ?string $check_out = null;
    public ?int $employee_id = null;
    public bool $isModalOpen = false;

    public function mount(): void
    {
        $this->date = now()->toDateString();
    }

    public function openModal(): void
    {
        $this->resetFields();
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetFields();
    }

    public function resetFields(): void
    {
        $this->reset(['employee_id', 'check_out']);
        $this->check_in = '09:00:00';
        $this->date = now()->toDateString();
    }

    public function save(HrService $service): void
    {
        $this->validate([
            'employee_id' => 'required|integer|exists:hr_employees,id',
            'date' => 'required|date',
            'check_in' => 'required|string',
            'check_out' => 'nullable|string',
        ]);

        $companyId = session('active_company_id', 1);

        $service->logAttendance(
            $this->employee_id,
            $this->date,
            $this->check_in,
            $this->check_out,
            $companyId
        );

        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Attendance logged successfully.')]);
    }

    public function render()
    {
        $companyId = session('active_company_id', 1);

        $attendances = Attendance::where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereHas('employee', function ($q2) {
                    $q2->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->with('employee')
            ->orderBy('date', 'desc')
            ->paginate(10);

        $employees = Employee::where('company_id', $companyId)->where('status', 'active')->get();

        return view('hr::livewire.admin.attendance.index', [
            'attendances' => $attendances,
            'employees' => $employees,
        ])->title(__('Attendance Logs'));
    }
}
