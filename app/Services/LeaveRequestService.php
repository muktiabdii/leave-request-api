<?php

namespace App\Services;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Cloudinary\Api\Upload\UploadApi;

class LeaveRequestService
{
    public function createLeaveRequest(array $data, $attachment)
    {
        $user = Auth::user();

        if ($user->leave_quota <= 0) {
            throw new \Exception('Leave quota is exhausted');
        }

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);

        if ($end->lt($start)) {
            throw new \Exception('End date cannot be before start date');
        }

        $totalDays = $start->diffInDays($end) + 1;

        if ($totalDays > $user->leave_quota) {
            throw new \Exception('Requested leave days exceed remaining quota');
        }

        $attachmentUrl = null;
        $attachmentId = null;

        $upload = (new UploadApi())->upload(
            $attachment->getRealPath(),
            [
                'folder' => 'leave_attachments'
            ]
        );

        $attachmentUrl = $upload['secure_url'];
        $attachmentId = $upload['public_id'];

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $user->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'total_days' => $totalDays,
            'reason' => $data['reason'],
            'attachment_url' => $attachmentUrl,
            'attachment_id' => $attachmentId,
            'status' => 'pending'
        ]);

        return $leaveRequest;
    }

    public function getMyLeaveRequests()
    {
        $user = Auth::user();

        return LeaveRequest::where('employee_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    public function getLeaveRequestById($id)
    {
        $user = Auth::user();

        $leaveRequest = LeaveRequest::where('employee_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$leaveRequest) {
            throw new \Exception('Leave request not found');
        }

        return $leaveRequest;
    }

    public function cancelLeaveRequest($id)
    {
        $user = Auth::user();

        $leaveRequest = LeaveRequest::where('employee_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$leaveRequest) {
            throw new \Exception('Leave request not found');
        }

        if ($leaveRequest->status !== 'pending') {
            throw new \Exception('Only pending leave requests can be cancelled');
        }

        $leaveRequest->status = 'cancelled';
        $leaveRequest->save();

        return $leaveRequest;
    }

    public function updateLeaveRequest($id, array $data, $attachment = null)
    {
        $user = Auth::user();

        $leaveRequest = LeaveRequest::where('employee_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$leaveRequest) {
            throw new \Exception('Leave request not found');
        }

        if ($leaveRequest->status !== 'pending') {
            throw new \Exception('Only pending leave requests can be updated');
        }

        $start = isset($data['start_date'])
            ? Carbon::parse($data['start_date'])
            : Carbon::parse($leaveRequest->start_date);

        $end = isset($data['end_date'])
            ? Carbon::parse($data['end_date'])
            : Carbon::parse($leaveRequest->end_date);

        $reason = isset($data['reason'])
            ? $data['reason']
            : $leaveRequest->reason;

        if ($end->lt($start)) {
            throw new \Exception('End date cannot be before start date');
        }

        $totalDays = $start->diffInDays($end) + 1;

        if ($totalDays > $user->leave_quota) {
            throw new \Exception('Requested leave days exceed remaining quota');
        }

        if ($attachment) {
            if ($leaveRequest->attachment_id) {
                (new UploadApi())->destroy($leaveRequest->attachment_id);
            }

            $upload = (new UploadApi())->upload(
                $attachment->getRealPath(),
                [
                    'folder' => 'leave_attachments'
                ]
            );

            $leaveRequest->attachment_url = $upload['secure_url'];
            $leaveRequest->attachment_id = $upload['public_id'];
        }

        $leaveRequest->start_date = $start->toDateString();
        $leaveRequest->end_date = $end->toDateString();
        $leaveRequest->total_days = $totalDays;
        $leaveRequest->reason = $reason;
        $leaveRequest->save();
        $leaveRequest->refresh();

        return $leaveRequest;
    }
}