<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceQrLog extends Model
{
    protected $fillable = [
        'attendance_id',
        'qr_code_id',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function qrCode()
    {
        return $this->belongsTo(OfficeQrCode::class, 'qr_code_id');
    }
}
