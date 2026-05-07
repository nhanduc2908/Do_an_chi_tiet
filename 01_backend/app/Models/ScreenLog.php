<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreenLog extends Model
{
    protected $table = 'screen_logs';

    protected $fillable = [
        'user_id', 'device_id', 'screen_width', 'screen_height',
        'device_pixel_ratio', 'orientation', 'user_agent', 'session_id',
    ];

    protected $casts = [
        'screen_width' => 'integer',
        'screen_height' => 'integer',
        'device_pixel_ratio' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(DeviceConnection::class, 'device_id', 'device_id');
    }
}