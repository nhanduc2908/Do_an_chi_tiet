<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrossDeviceSession extends Model
{
    protected $fillable = [
        'user_id', 'session_token', 'connected_devices', 'active',
        'started_at', 'ended_at',
    ];

    protected $casts = [
        'connected_devices' => 'array',
        'active' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}