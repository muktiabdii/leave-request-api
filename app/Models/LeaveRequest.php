<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'approved_by',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment_url',
        'attachment_id',
        'status',
        'admin_note',
        'approved_at',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
