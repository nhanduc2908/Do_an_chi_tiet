<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DarkWebMonitor extends Model
{
    protected $table = 'dark_web_monitors';

    protected $fillable = [
        'keyword', 'source', 'content', 'severity',
        'detected_at', 'is_reviewed',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'is_reviewed' => 'boolean',
    ];
}