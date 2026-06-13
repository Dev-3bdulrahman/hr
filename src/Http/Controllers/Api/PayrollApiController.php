<?php

namespace Dev3bdulrahman\Hr\Http\Controllers\Api;

use Dev3bdulrahman\Hr\Models\Payroll;
use Dev3bdulrahman\Hr\Http\Resources\PayrollResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayrollApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $companyId = $request->header('X-Company-ID') ?? 1;

        $payrolls = Payroll::where('company_id', $companyId)
            ->with('employee')
            ->get();

        return response()->json([
            'success' => true,
            'message' => __('Payrolls retrieved successfully.'),
            'data' => PayrollResource::collection($payrolls),
        ]);
    }
}
