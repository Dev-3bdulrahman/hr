<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Hr\Events\PayrollGenerated;
use Dev3bdulrahman\Hr\Http\Requests\Api\StorePayrollApiRequest;
use Dev3bdulrahman\Hr\Http\Resources\PayrollResource;
use Dev3bdulrahman\Hr\Models\Payroll;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollApiController extends Controller
{
    use HasApiResponse;

    /**
     * List all payrolls.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Payroll::class);

        $companyId = $request->header('X-Company-ID') ?? auth()->user()->company_id;

        $payrolls = Payroll::where('company_id', $companyId)
            ->with('employee')
            ->get();

        return $this->success(
            PayrollResource::collection($payrolls),
            __('hr::hr.payrolls_retrieved')
        );
    }

    /**
     * Store a new payroll record.
     */
    public function store(StorePayrollApiRequest $request): JsonResponse
    {
        $this->authorize('create', Payroll::class);

        $validated = $request->validated();
        $validated['company_id'] = $request->header('X-Company-ID') ?? auth()->user()->company_id;

        $payroll = Payroll::create($validated);

        PayrollGenerated::dispatch($payroll, auth()->id(), $validated['company_id']);

        return $this->success(
            new PayrollResource($payroll->load('employee')),
            __('hr::hr.payroll_created'),
            201
        );
    }

    /**
     * Show a single payroll.
     */
    public function show(Payroll $payroll): JsonResponse
    {
        $this->authorize('view', $payroll);

        $payroll->load('employee');

        return $this->success(
            new PayrollResource($payroll),
            __('hr::hr.payroll_details_retrieved')
        );
    }

    /**
     * Approve a payroll.
     */
    public function approve(Payroll $payroll): JsonResponse
    {
        $this->authorize('approve', $payroll);

        $payroll->update(['status' => 'approved', 'approved_by' => auth()->id()]);

        return $this->success(
            new PayrollResource($payroll->fresh()),
            __('hr::hr.payroll_approved')
        );
    }

    /**
     * Delete a payroll.
     */
    public function destroy(Payroll $payroll): JsonResponse
    {
        $this->authorize('delete', $payroll);

        $payroll->delete();

        return $this->success(
            null,
            __('hr::hr.payroll_deleted')
        );
    }
}
