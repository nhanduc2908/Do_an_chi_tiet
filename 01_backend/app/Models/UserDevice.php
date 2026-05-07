<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id', 'device_id', 'device_name', 'device_type',
        'ip_address', 'user_agent', 'session_token', 'last_login_at',
        'is_verified', 'is_active', 'push_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}