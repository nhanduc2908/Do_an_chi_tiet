<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateLimit extends Model
{
    protected $fillable = [
        'key', 'attempts', 'max_attempts', 'decay_minutes',
        'blocked_at', 'ip_address', 'user_id',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'max_attempts' => 'integer',
        'decay_minutes' => 'integer',
        'blocked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isBlocked()
    {
        return $this->blocked_at && $this->blocked_at->addMinutes($this->decay_minutes)->isFuture();
    }
}