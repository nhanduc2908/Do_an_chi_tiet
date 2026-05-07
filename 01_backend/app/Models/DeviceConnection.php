<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceConnection extends Model
{
    protected $table = 'device_connections';

    protected $fillable = [
        'user_id', 'device_id', 'device_name', 'device_type',
        'push_token', 'is_connected', 'last_connected_at',
        'last_sync_at', 'sync_version',
    ];

    protected $casts = [
        'is_connected' => 'boolean',
        'last_connected_at' => 'datetime',
        'last_sync_at' => 'datetime',
        'sync_version' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}