<?php

namespace Dev3bdulrahman\Hr\Listeners;

use App\Services\AuditLogService;
use Dev3bdulrahman\Hr\Events\PayrollGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogPayrollGenerated implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private AuditLogService $auditLogService,
    ) {}

    /**
     * Handle the PayrollGenerated event.
     */
    public function handle(PayrollGenerated $event): void
    {
        try {
            $this->auditLogService->log(
                action: 'payroll_generated',
                companyId: $event->companyId,
                userId: $event->userId,
                model: $event->payroll,
                oldValues: null,
                newValues: [
                    'payroll_id' => $event->payroll->id,
                    'employee_id' => $event->payroll->employee_id,
                    'month' => $event->payroll->month,
                    'year' => $event->payroll->year,
                    'basic_salary' => $event->payroll->basic_salary,
                ],
            );
        } catch (\Throwable $e) {
            Log::error('LogPayrollGenerated: Failed to log payroll generated.', [
                'error' => $e->getMessage(),
                'payroll_id' => $event->payroll->id ?? null,
                'user_id' => $event->userId ?? null,
            ]);
        }
    }
}
