<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Web\Admin\Payroll;

use Dev3bdulrahman\Hr\Models\Payroll;
use Dev3bdulrahman\Hr\Services\HrService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $month = 0;
    public int $year = 0;
    public bool $isModalOpen = false;

    public function mount(): void
    {
        $this->month = (int)now()->format('m');
        $this->year = (int)now()->format('Y');
    }

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    public function generate(HrService $service): void
    {
        $this->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2050',
        ]);

        $companyId = session('active_company_id', 1);
        $service->generatePayroll($companyId, $this->month, $this->year);

        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Payroll generated successfully.')]);
    }

    public function pay(int $id, HrService $service): void
    {
        $payroll = Payroll::findOrFail($id);
        $service->payPayroll($payroll);
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Payroll status updated to Paid.')]);
    }

    public function render()
    {
        $companyId = session('active_company_id', 1);

        $payrolls = Payroll::where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereHas('employee', function ($q2) {
                    $q2->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->with('employee')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);

        return view('hr::livewire.admin.payroll.index', [
            'payrolls' => $payrolls,
        ])->title(__('Payrolls'));
    }
}
