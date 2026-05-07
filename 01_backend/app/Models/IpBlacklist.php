<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpBlacklist extends Model
{
    protected $table = 'ip_blacklist';

    protected $fillable = [
        'ip_address', 'reason', 'blocked_by', 'blocked_at', 'expires_at',
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function isActive()
    {
        return is_null($this->expires_at) || $this->expires_at->isFuture();
    }
}