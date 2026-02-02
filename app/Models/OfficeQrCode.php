<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeQrCode extends Model
{
    protected $fillable = [
        'code',
        'valid_from',
        'valid_until',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function qrLogs()
    {
        return $this->hasMany(AttendanceQrLog::class, 'qr_code_id');
    }
}
