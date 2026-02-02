<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'attendance_id',
        'action',
        'note',
        'action_by',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
