<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\ApiResponse;
use App\Services\LeaveRequestService;
use App\Http\Resources\AdminLeaveRequestResource;
use App\Http\Requests\ApproveLeaveRequest;
use App\Http\Requests\CreateLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Http\Resources\LeaveRequestResource;

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

    public function index()
    {
        try {

            $leaveRequests = $this->leaveRequestService->getMyLeaveRequests();

            $meta = [
                'page' => $leaveRequests->currentPage(),
                'limit' => $leaveRequests->perPage(),
                'total' => $leaveRequests->total(),
                'total_pages' => $leaveRequests->lastPage(),
                'has_next' => $leaveRequests->hasMorePages(),
                'has_prev' => $leaveRequests->currentPage() > 1
            ];

            return ApiResponse::success(
                LeaveRequestResource::collection($leaveRequests),
                'Leave requests retrieved successfully',
                200,
                $meta
            );

        } catch (\Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                400
            );
        }
    }

    public function show($id)
    {
        try {

            $leaveRequest = $this->leaveRequestService->getLeaveRequestById($id);

            return ApiResponse::success(
                new LeaveRequestResource($leaveRequest),
                'Leave request retrieved successfully'
            );

        } catch (\Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                404
            );
        }
    }

    public function cancel($id)
    {
        try {

            $leaveRequest = $this->leaveRequestService->cancelLeaveRequest($id);

            return ApiResponse::success(
                new LeaveRequestResource($leaveRequest),
                'Leave request cancelled successfully'
            );

        } catch (\Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                400
            );
        }
    }

    public function update($id, UpdateLeaveRequest $request)
    {
        try {

            $leaveRequest = $this->leaveRequestService->updateLeaveRequest(
                $id,
                $request->validated(),
                $request->file('attachment')
            );

            return ApiResponse::success(
                new LeaveRequestResource($leaveRequest),
                'Leave request updated successfully'
            );

        } catch (\Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                400
            );
        }
    }

    public function adminIndex(Request $request)
    {
        try {
            $filters = $request->only(['status', 'employee_id', 'start_date', 'end_date', 'sort', 'order']);

            $leaveRequests = $this->leaveRequestService->getLeaveRequestsForAdmin($filters);

            $meta = [
                'page' => $leaveRequests->currentPage(),
                'limit' => $leaveRequests->perPage(),
                'total' => $leaveRequests->total(),
                'total_pages' => $leaveRequests->lastPage(),
                'has_next' => $leaveRequests->hasMorePages(),
                'has_prev' => $leaveRequests->currentPage() > 1
            ];

            return ApiResponse::success(
                AdminLeaveRequestResource::collection($leaveRequests),
                'Leave requests retrieved successfully',
                200,
                $meta
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                400
            );
        }
    }

    public function adminShow($id)
    {
        try {
            $leaveRequest = $this->leaveRequestService->getLeaveRequestForAdmin($id);

            return ApiResponse::success(
                new AdminLeaveRequestResource($leaveRequest),
                'Leave request retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                404
            );
        }
    }

    public function approve($id, ApproveLeaveRequest $request)
    {
        try {
            $leaveRequest = $this->leaveRequestService->approveLeaveRequest(
                $id,
                $request->validated()
            );

            $message = $request->status === 'approved'
                ? 'Leave request approved successfully'
                : 'Leave request rejected successfully';

            return ApiResponse::success(
                new LeaveRequestResource($leaveRequest),
                $message
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                400
            );
        }
    }
}
