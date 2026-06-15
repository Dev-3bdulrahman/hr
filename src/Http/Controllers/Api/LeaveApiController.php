<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Hr\Events\LeaveRequestApproved;
use Dev3bdulrahman\Hr\Events\LeaveRequestSubmitted;
use Dev3bdulrahman\Hr\Http\Requests\Api\StoreLeaveRequestApiRequest;
use Dev3bdulrahman\Hr\Http\Resources\LeaveResource;
use Dev3bdulrahman\Hr\Models\LeaveRequest;
use Dev3bdulrahman\Hr\Services\HrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveApiController extends Controller
{
    use HasApiResponse;

    /**
     * List all leave requests.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', LeaveRequest::class);

        $companyId = $request->header('X-Company-ID') ?? auth()->user()->company_id;

        $leaves = LeaveRequest::where('company_id', $companyId)
            ->with(['employee', 'leaveType'])
            ->get();

        return $this->success(
            LeaveResource::collection($leaves),
            __('hr::hr.leaves_retrieved')
        );
    }

    /**
     * Submit a leave request.
     */
    public function store(StoreLeaveRequestApiRequest $request, HrService $service): JsonResponse
    {
        $this->authorize('create', LeaveRequest::class);

        $companyId = $request->header('X-Company-ID') ?? auth()->user()->company_id;

        $leave = $service->requestLeave($request->validated(), $companyId);

        LeaveRequestSubmitted::dispatch($leave, auth()->id(), $companyId);

        return $this->success(
            new LeaveResource($leave),
            __('hr::hr.leave_requested'),
            201
        );
    }

    /**
     * Show a single leave request.
     */
    public function show(LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorize('view', $leaveRequest);

        $leaveRequest->load(['employee', 'leaveType']);

        return $this->success(
            new LeaveResource($leaveRequest),
            __('hr::hr.leave_details_retrieved')
        );
    }

    /**
     * Approve a leave request.
     */
    public function approve(LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorize('approve', $leaveRequest);

        $leaveRequest->update(['status' => 'approved', 'approved_by' => auth()->id()]);

        LeaveRequestApproved::dispatch($leaveRequest, auth()->id(), $leaveRequest->company_id);

        return $this->success(
            new LeaveResource($leaveRequest->fresh()),
            __('hr::hr.leave_approved')
        );
    }

    /**
     * Delete a leave request.
     */
    public function destroy(LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorize('delete', $leaveRequest);

        $leaveRequest->delete();

        return $this->success(
            null,
            __('hr::hr.leave_deleted')
        );
    }
}
