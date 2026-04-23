<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'operator_id',
        'ticket_code',
        'subject',
        'description',
        'priority',
        'status',
        'first_response_at',
        'resolved_at',
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'resolved_at'       => 'datetime',
    ];

    /**
     * Auto-generate ticket_code saat dibuat.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $date    = now()->format('Ymd');
            $count   = self::whereDate('created_at', today())->count() + 1;
            $ticket->ticket_code = 'TKT-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }

    // ─── Relasi ────────────────────────────────────────────────────────────────

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class)->oldest();
    }

    public function rating()
    {
        return $this->hasOne(SatisfactionRating::class);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'high'  => '<span class="badge" style="background:#fee2e2;color:#991b1b;">HIGH</span>',
            'mid'   => '<span class="badge" style="background:#fef3c7;color:#92400e;">MID</span>',
            default => '<span class="badge" style="background:#f1f5f9;color:#475569;">LOW</span>',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => '<span class="badge" style="background:#dbeafe;color:#1e40af;">IN PROGRESS</span>',
            'closed'      => '<span class="badge" style="background:#dcfce7;color:#166534;">CLOSED</span>',
            default       => '<span class="badge" style="background:#fef3c7;color:#92400e;">OPEN</span>',
        };
    }

    /**
     * Response time dalam menit (sejak dibuat hingga respons pertama).
     */
    public function getResponseTimeMinutesAttribute(): ?int
    {
        if (! $this->first_response_at) return null;
        return (int) $this->created_at->diffInMinutes($this->first_response_at);
    }

    /**
     * Resolution time dalam menit (sejak dibuat hingga closed).
     */
    public function getResolutionTimeMinutesAttribute(): ?int
    {
        if (! $this->resolved_at) return null;
        return (int) $this->created_at->diffInMinutes($this->resolved_at);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}
