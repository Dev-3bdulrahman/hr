<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Api;

use Dev3bdulrahman\Hr\Models\Employee;
use Dev3bdulrahman\Hr\Http\Resources\EmployeeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $companyId = $request->header('X-Company-ID') ?? 1;

        $employees = Employee::where('company_id', $companyId)
            ->with(['department', 'designation', 'activeContract'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => __('Employees retrieved successfully.'),
            'data' => EmployeeResource::collection($employees),
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $companyId = $request->header('X-Company-ID') ?? 1;

        $employee = Employee::where('company_id', $companyId)
            ->with(['department', 'designation', 'activeContract'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => __('Employee details retrieved successfully.'),
            'data' => new EmployeeResource($employee),
        ]);
    }
}
