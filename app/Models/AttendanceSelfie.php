<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSelfie extends Model
{
    protected $fillable = [
        'attendance_id',
        'type',
        'image_path',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
