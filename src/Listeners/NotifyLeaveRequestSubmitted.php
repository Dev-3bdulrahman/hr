<?php

namespace Dev3bdulrahman\Hr\Listeners;

use Dev3bdulrahman\Hr\Events\LeaveRequestSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class NotifyLeaveRequestSubmitted implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the LeaveRequestSubmitted event.
     */
    public function handle(LeaveRequestSubmitted $event): void
    {
        try {
            $managers = User::where('company_id', $event->companyId)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['admin', 'hr_manager', 'manager']);
                })
                ->get();

            foreach ($managers as $manager) {
                $manager->notify(
                    new \Illuminate\Notifications\DatabaseNotification([
                        'type' => 'leave_request_submitted',
                        'message' => __('hr::hr.leave_request_submitted_notification'),
                        'leave_request_id' => $event->leaveRequest->id,
                        'employee_id' => $event->leaveRequest->employee_id,
                        'submitted_by' => $event->userId,
                    ])
                );
            }
        } catch (\Throwable $e) {
            Log::error('NotifyLeaveRequestSubmitted: Failed to notify managers.', [
                'error' => $e->getMessage(),
                'leave_request_id' => $event->leaveRequest->id ?? null,
                'user_id' => $event->userId ?? null,
            ]);
        }
    }
}
