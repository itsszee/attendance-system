<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatisfactionRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'score',
        'feedback',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
