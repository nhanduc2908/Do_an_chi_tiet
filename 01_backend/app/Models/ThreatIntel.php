<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreatIntel extends Model
{
    protected $table = 'threat_intel';

    protected $fillable = [
        'indicator', 'type', 'severity', 'description',
        'sources', 'first_seen', 'last_seen', 'is_active',
    ];

    protected $casts = [
        'sources' => 'array',
        'first_seen' => 'datetime',
        'last_seen' => 'datetime',
        'is_active' => 'boolean',
    ];
}