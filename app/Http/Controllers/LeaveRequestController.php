<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Services\LeaveRequestService;
use App\Http\Resources\LeaveRequestResource;
use App\Http\Requests\CreateLeaveRequest;

class LeaveRequestController extends Controller
{
    public function __construct(
        protected LeaveRequestService $leaveRequestService
    ) {
    }

    public function store(CreateLeaveRequest $request)
    {
        try {

            $leaveRequest = $this->leaveRequestService->createLeaveRequest(
                $request->validated(),
                $request->file('attachment') 
            );

            return ApiResponse::success(
                new LeaveRequestResource($leaveRequest),
                'Leave request created successfully',
                201
            );
        } catch (\Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                400
            );
        }
    }
}
