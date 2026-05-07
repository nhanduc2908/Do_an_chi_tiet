<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncQueue extends Model
{
    protected $fillable = [
        'type', 'data', 'priority', 'status', 'attempts',
        'processed_at', 'error_message',
    ];

    protected $casts = [
        'data' => 'array',
        'processed_at' => 'datetime',
    ];
}