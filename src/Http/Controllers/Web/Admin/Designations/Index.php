<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Designations;

use Dev3bdulrahman\Hr\Models\Designation;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.admin')]
class Index extends Component
{
    public string $search = '';
    public bool $isModalOpen = false;
    public ?int $designation_id = null;

    public string $name = '';
    public string $level = '';
    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'level'  => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function openModal(?int $id = null): void
    {
        $this->resetFields();
        if ($id) {
            $desg = Designation::findOrFail($id);
            $this->designation_id = $desg->id;
            $this->name           = $desg->name;
            $this->level          = $desg->level ?? '';
            $this->status         = $desg->status;
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
        $this->reset(['designation_id', 'name', 'level']);
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
            'level'      => $this->level ?: null,
            'status'     => $this->status,
        ];

        if ($this->designation_id) {
            Designation::findOrFail($this->designation_id)->update($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('hr::hr.designation_updated')]);
        } else {
            Designation::create($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('hr::hr.designation_created')]);
        }

        $this->closeModal();
    }

    #[On('delete')]
    public function delete($id): void
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            Designation::findOrFail($targetId)->delete();
            $this->dispatch('notify', ['type' => 'success', 'message' => __('hr::hr.designation_deleted')]);
        }
    }

    public function render()
    {
        $companyId = session('active_company_id', 1);

        $designations = Designation::where('company_id', $companyId)
            ->where('name', 'like', '%' . $this->search . '%')
            ->withCount(['employees'])
            ->orderBy('name')
            ->paginate(15);

        return view('hr::livewire.admin.designations.index', [
            'designations' => $designations,
        ])->title(__('hr::hr.designations'));
    }
}
