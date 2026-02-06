<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'mode',
        'check_in_at',
        'check_out_at',
        'status',
        'approval_status',
        'task',
        'latitude',
        'longitude',
        'selfie_path',
    ];


    protected $casts = [
        'date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->hasOne(AttendanceLocation::class);
    }

    public function selfies()
    {
        return $this->hasMany(AttendanceSelfie::class);
    }

    public function tasks()
    {
        return $this->hasMany(AttendanceTask::class);
    }

    public function qrLogs()
    {
        return $this->hasMany(AttendanceQrLog::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
