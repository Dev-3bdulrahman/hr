<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Hr\Http\Requests\Api\StoreAttendanceApiRequest;
use Dev3bdulrahman\Hr\Http\Resources\AttendanceResource;
use Dev3bdulrahman\Hr\Models\Attendance;
use Dev3bdulrahman\Hr\Services\HrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    use HasApiResponse;

    /**
     * List all attendance records.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Attendance::class);

        $companyId = $request->header('X-Company-ID') ?? auth()->user()->company_id;

        $attendances = Attendance::where('company_id', $companyId)
            ->with('employee')
            ->get();

        return $this->success(
            AttendanceResource::collection($attendances),
            __('hr::hr.attendance_retrieved')
        );
    }

    /**
     * Log attendance for an employee.
     */
    public function store(StoreAttendanceApiRequest $request, HrService $service): JsonResponse
    {
        $this->authorize('create', Attendance::class);

        $companyId = $request->header('X-Company-ID') ?? auth()->user()->company_id;

        $attendance = $service->logAttendance(
            $request->input('employee_id'),
            $request->input('date'),
            $request->input('check_in'),
            $request->input('check_out'),
            $companyId
        );

        return $this->success(
            new AttendanceResource($attendance),
            __('hr::hr.attendance_logged'),
            201
        );
    }

    /**
     * Show a single attendance record.
     */
    public function show(Attendance $attendance): JsonResponse
    {
        $this->authorize('view', $attendance);

        $attendance->load('employee');

        return $this->success(
            new AttendanceResource($attendance),
            __('hr::hr.attendance_details_retrieved')
        );
    }

    /**
     * Delete an attendance record.
     */
    public function destroy(Attendance $attendance): JsonResponse
    {
        $this->authorize('delete', $attendance);

        $attendance->delete();

        return $this->success(
            null,
            __('hr::hr.attendance_deleted')
        );
    }
}
