<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceTask extends Model
{
    protected $fillable = [
        'attendance_id',
        'description',
        'progress_status',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
