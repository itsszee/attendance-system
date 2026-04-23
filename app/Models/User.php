<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    // Property
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'google_token',
        'google_refresh_token',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    // Casts untuk atribut tertentu ebuah class memiliki banyak bentuk, biasanya melalui overriding (mengganti) method.
    //melakukan overriding terhadap method casts() yang aslinya didefinisikan di base Class Model. 
    // ini menunjukkan perilaku yang berbeda (polymorphic) untuk tipe model
    // Polymorphism / Overriding 
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function getStatusBadgeAttribute()
    {
        $roles = [
            'admin' => '<span class="badge bg-danger">Admin</span>',
            'karyawan' => '<span class="badge bg-primary">Karyawan</span>',
            'user' => '<span class="badge bg-secondary">User</span>',
        ];
        
        return $roles[$this->role] ?? '<span class="badge bg-secondary">User</span>';
    }

    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relation to karyawan record based on email match.
     */
    public function karyawan()
    {
        return $this->hasOne(\App\Models\Karyawan::class, 'email', 'email');
    }

    
    public function createdQrCodes()
    {
        return $this->hasMany(OfficeQrCode::class, 'created_by');
    }

    
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'action_by');
    }

    public function employeeRequests()
    {
        return $this->hasMany(EmployeeRequest::class);
    }

    // ─── Helpdesk Relasi ───────────────────────────────────────────────────────

    public function reportedTickets()
    {
        return $this->hasMany(Ticket::class, 'reporter_id');
    }

    public function operatedTickets()
    {
        return $this->hasMany(Ticket::class, 'operator_id');
    }

    public function satisfactionRatings()
    {
        return $this->hasMany(SatisfactionRating::class);
    }
}
