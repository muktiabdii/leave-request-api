<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_days' => $this->total_days,
            'reason' => $this->reason,
            'attachment_url' => $this->attachment_url,
            'attachment_id' => $this->attachment_id,
            'status' => $this->status,
            'admin_note' => $this->admin_note,
            'approved_by' => [
                'id' => $this->approver ? $this->approver->id : null,
                'name' => $this->approver ? $this->approver->name : null,
            ],
            'approved_at' => $this->approved_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
