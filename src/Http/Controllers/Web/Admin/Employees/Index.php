<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Employees;

use Dev3bdulrahman\Hr\Models\Employee;
use Dev3bdulrahman\Hr\Models\Department;
use Dev3bdulrahman\Hr\Models\Designation;
use Dev3bdulrahman\Hr\Services\HrService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.admin')]
class Index extends Component
{
    public string $search = '';
    public bool $isModalOpen = false;
    public ?int $employee_id = null;

    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $hire_date = '';
    public string $status = 'active';
    public ?int $department_id = null;
    public ?int $designation_id = null;
    public float $salary = 0.0;

    protected function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'status' => 'required|string|in:active,inactive',
            'department_id' => 'nullable|integer|exists:hr_departments,id',
            'designation_id' => 'nullable|integer|exists:hr_designations,id',
            'salary' => 'nullable|numeric|min:0',
        ];
    }

    public function openModal(?int $id = null): void
    {
        $this->resetFields();
        if ($id) {
            $this->edit($id);
        }
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetFields();
    }

    public function resetFields(): void
    {
        $this->reset(['employee_id', 'first_name', 'last_name', 'email', 'phone', 'hire_date', 'department_id', 'designation_id', 'salary']);
        $this->status = 'active';
        $this->resetValidation();
    }

    public function edit(int $id): void
    {
        $emp = Employee::findOrFail($id);
        $this->employee_id = $emp->id;
        $this->first_name = $emp->first_name;
        $this->last_name = $emp->last_name;
        $this->email = $emp->email ?? '';
        $this->phone = $emp->phone ?? '';
        $this->hire_date = $emp->hire_date ? $emp->hire_date->toDateString() : '';
        $this->status = $emp->status;
        $this->department_id = $emp->department_id;
        $this->designation_id = $emp->designation_id;
        $this->salary = $emp->activeContract ? (float)$emp->activeContract->salary : 0.0;
        $this->isModalOpen = true;
    }

    public function save(HrService $service): void
    {
        $this->validate();
        $companyId = session('active_company_id', 1);

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'hire_date' => $this->hire_date,
            'status' => $this->status,
            'department_id' => $this->department_id,
            'designation_id' => $this->designation_id,
            'salary' => $this->salary,
        ];

        if ($this->employee_id) {
            $emp = Employee::findOrFail($this->employee_id);
            $service->updateEmployee($emp, $data);
        } else {
            $service->createEmployee($data, $companyId);
        }

        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Employee saved successfully.')]);
    }

    public function toggleStatus(int $id, HrService $service): void
    {
        $emp = Employee::findOrFail($id);
        $service->toggleEmployeeStatus($emp);
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Employee status updated successfully.')]);
    }

    #[On('delete')]
    public function delete($id, HrService $service): void
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $emp = Employee::findOrFail($targetId);
            $service->deleteEmployee($emp);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Employee deleted successfully.')]);
        }
    }

    public function render()
    {
        $companyId = session('active_company_id', 1);

        $employees = Employee::where('company_id', $companyId)
            ->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->with(['department', 'designation', 'activeContract'])
            ->paginate(10);

        $departments = Department::where('company_id', $companyId)->get();
        $designations = Designation::where('company_id', $companyId)->get();

        return view('hr::livewire.admin.employees.index', [
            'employees' => $employees,
            'departments' => $departments,
            'designations' => $designations,
        ])->title(__('Employees'));
    }
}
