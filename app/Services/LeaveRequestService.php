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
}