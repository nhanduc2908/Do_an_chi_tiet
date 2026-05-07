<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceMessage extends Model
{
    protected $fillable = [
        'from_device_id', 'to_device_id', 'message_type', 'payload',
        'is_read', 'read_at', 'delivered_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function fromDevice()
    {
        return $this->belongsTo(DeviceConnection::class, 'from_device_id', 'device_id');
    }

    public function toDevice()
    {
        return $this->belongsTo(DeviceConnection::class, 'to_device_id', 'device_id');
    }
}