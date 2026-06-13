<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Api;

use Dev3bdulrahman\Hr\Services\HrService;
use Dev3bdulrahman\Hr\Http\Resources\LeaveResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveApiController extends Controller
{
    public function request(Request $request, HrService $service): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:hr_employees,id',
            'leave_type_id' => 'required|integer|exists:hr_leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        $companyId = $request->header('X-Company-ID') ?? 1;

        $leave = $service->requestLeave([
            'employee_id' => $request->input('employee_id'),
            'leave_type_id' => $request->input('leave_type_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'reason' => $request->input('reason'),
        ], $companyId);

        return response()->json([
            'success' => true,
            'message' => __('Leave requested successfully.'),
            'data' => new LeaveResource($leave),
        ]);
    }
}
