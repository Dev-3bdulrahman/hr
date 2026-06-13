<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Departments;

use Dev3bdulrahman\Hr\Models\Department;
use Dev3bdulrahman\Hr\Models\Employee;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.admin')]
class Index extends Component
{
    public string $search = '';
    public bool $isModalOpen = false;
    public ?int $department_id = null;

    public string $name = '';
    public string $code = '';
    public ?int $parent_id = null;
    public ?int $manager_id = null;
    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'code'      => 'nullable|string|max:50',
            'parent_id' => 'nullable|integer|exists:hr_departments,id',
            'manager_id'=> 'nullable|integer|exists:hr_employees,id',
            'status'    => 'required|in:active,inactive',
        ];
    }

    public function openModal(?int $id = null): void
    {
        $this->resetFields();
        if ($id) {
            $dept = Department::findOrFail($id);
            $this->department_id = $dept->id;
            $this->name          = $dept->name;
            $this->code          = $dept->code ?? '';
            $this->parent_id     = $dept->parent_id;
            $this->manager_id    = $dept->manager_id;
            $this->status        = $dept->status;
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
        $this->reset(['department_id', 'name', 'code', 'parent_id', 'manager_id']);
        $this->status = 'active';
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();
        $companyId = session('active_company_id', 1);

        $data = [
            'company_id' => $companyId,
            'name'       => $this->name,
            'code'       => $this->code ?: null,
            'parent_id'  => $this->parent_id,
            'manager_id' => $this->manager_id,
            'status'     => $this->status,
        ];

        if ($this->department_id) {
            Department::findOrFail($this->department_id)->update($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('hr::hr.department_updated')]);
        } else {
            Department::create($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('hr::hr.department_created')]);
        }

        $this->closeModal();
    }

    #[On('delete')]
    public function delete($id): void
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            Department::findOrFail($targetId)->delete();
            $this->dispatch('notify', ['type' => 'success', 'message' => __('hr::hr.department_deleted')]);
        }
    }

    public function render()
    {
        $companyId = session('active_company_id', 1);

        $departments = Department::where('company_id', $companyId)
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->withCount(['employees'])
            ->with(['parent', 'manager'])
            ->orderBy('name')
            ->paginate(15);

        $allDepartments = Department::where('company_id', $companyId)->get();
        $managers = Employee::where('company_id', $companyId)->where('status', 'active')->get();

        return view('hr::livewire.admin.departments.index', [
            'departments'    => $departments,
            'allDepartments' => $allDepartments,
            'managers'       => $managers,
        ])->title(__('hr::hr.departments'));
    }
}
