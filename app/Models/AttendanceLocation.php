<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLocation extends Model
{
    protected $fillable = [
        'attendance_id',
        'latitude',
        'longitude',
        'accuracy',
        'address',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
