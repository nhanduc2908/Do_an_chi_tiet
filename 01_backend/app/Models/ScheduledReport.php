<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model
{
    protected $fillable = [
        'name', 'type', 'format', 'cron', 'recipients', 'filters',
        'is_active', 'last_sent_at',
    ];

    protected $casts = [
        'recipients' => 'array',
        'filters' => 'array',
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
    ];
}