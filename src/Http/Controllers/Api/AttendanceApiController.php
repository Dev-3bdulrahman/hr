<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Api;

use Dev3bdulrahman\Hr\Services\HrService;
use Dev3bdulrahman\Hr\Http\Resources\AttendanceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceApiController extends Controller
{
    public function log(Request $request, HrService $service): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:hr_employees,id',
            'date' => 'required|date',
            'check_in' => 'required|string',
            'check_out' => 'nullable|string',
        ]);

        $companyId = $request->header('X-Company-ID') ?? 1;

        $attendance = $service->logAttendance(
            $request->input('employee_id'),
            $request->input('date'),
            $request->input('check_in'),
            $request->input('check_out'),
            $companyId
        );

        return response()->json([
            'success' => true,
            'message' => __('Attendance logged successfully.'),
            'data' => new AttendanceResource($attendance),
        ]);
    }
}
