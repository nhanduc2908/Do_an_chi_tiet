<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirewallRule extends Model
{
    protected $fillable = [
        'name', 'source_ip', 'destination_ip', 'port',
        'protocol', 'action', 'priority', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];
}