<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Hr\Http\Requests\Api\StoreEmployeeApiRequest;
use Dev3bdulrahman\Hr\Http\Requests\Api\UpdateEmployeeApiRequest;
use Dev3bdulrahman\Hr\Http\Resources\EmployeeResource;
use Dev3bdulrahman\Hr\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeApiController extends Controller
{
    use HasApiResponse;

    /**
     * List all employees.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Employee::class);

        $companyId = $request->header('X-Company-ID') ?? auth()->user()->company_id;

        $employees = Employee::where('company_id', $companyId)
            ->with(['department', 'designation', 'activeContract'])
            ->get();

        return $this->success(
            EmployeeResource::collection($employees),
            __('hr::hr.employees_retrieved')
        );
    }

    /**
     * Show a single employee.
     */
    public function show(Employee $employee): JsonResponse
    {
        $this->authorize('view', $employee);

        $employee->load(['department', 'designation', 'activeContract']);

        return $this->success(
            new EmployeeResource($employee),
            __('hr::hr.employee_details_retrieved')
        );
    }

    /**
     * Store a new employee.
     */
    public function store(StoreEmployeeApiRequest $request): JsonResponse
    {
        $this->authorize('create', Employee::class);

        $validated = $request->validated();
        $validated['company_id'] = $request->header('X-Company-ID') ?? auth()->user()->company_id;

        $employee = Employee::create($validated);
        $employee->load(['department', 'designation']);

        return $this->success(
            new EmployeeResource($employee),
            __('hr::hr.employee_created'),
            201
        );
    }

    /**
     * Update an existing employee.
     */
    public function update(UpdateEmployeeApiRequest $request, Employee $employee): JsonResponse
    {
        $this->authorize('update', $employee);

        $employee->update($request->validated());
        $employee->load(['department', 'designation', 'activeContract']);

        return $this->success(
            new EmployeeResource($employee),
            __('hr::hr.employee_updated')
        );
    }

    /**
     * Delete an employee.
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return $this->success(
            null,
            __('hr::hr.employee_deleted')
        );
    }
}
