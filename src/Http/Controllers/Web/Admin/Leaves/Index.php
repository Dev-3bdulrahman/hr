<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Leaves;

use Dev3bdulrahman\Hr\Models\LeaveRequest;
use Dev3bdulrahman\Hr\Models\LeaveType;
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
    public bool $isModalOpen = false;

    public ?int $employee_id = null;
    public ?int $leave_type_id = null;
    public string $start_date = '';
    public string $end_date = '';
    public string $reason = '';

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
        $this->reset(['employee_id', 'leave_type_id', 'start_date', 'end_date', 'reason']);
        $this->resetValidation();
    }

    public function save(HrService $service): void
    {
        $this->validate([
            'employee_id' => 'required|integer|exists:hr_employees,id',
            'leave_type_id' => 'required|integer|exists:hr_leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        $companyId = session('active_company_id', 1);

        $service->requestLeave([
            'employee_id' => $this->employee_id,
            'leave_type_id' => $this->leave_type_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
        ], $companyId);

        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Leave requested successfully.')]);
    }

    public function approve(int $id, HrService $service): void
    {
        $request = LeaveRequest::findOrFail($id);
        $service->processLeave($request, 'approved', auth()->id() ?? 1);
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Leave approved successfully.')]);
    }

    public function reject(int $id, HrService $service): void
    {
        $request = LeaveRequest::findOrFail($id);
        $service->processLeave($request, 'rejected', auth()->id() ?? 1);
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Leave rejected successfully.')]);
    }

    public function render()
    {
        $companyId = session('active_company_id', 1);

        $leaves = LeaveRequest::where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereHas('employee', function ($q2) {
                    $q2->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['employee', 'leaveType', 'approver'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $employees = Employee::where('company_id', $companyId)->where('status', 'active')->get();
        $leaveTypes = LeaveType::where('company_id', $companyId)->get();

        return view('hr::livewire.admin.leaves.index', [
            'leaves' => $leaves,
            'employees' => $employees,
            'leaveTypes' => $leaveTypes,
        ])->title(__('Leave Requests'));
    }
}
