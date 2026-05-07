<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SIEMAlert extends Model
{
    protected $table = 'siem_alerts';

    protected $fillable = [
        'alert_id', 'source', 'severity', 'rule_name',
        'description', 'raw_data', 'occurred_at', 'is_resolved',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'occurred_at' => 'datetime',
        'is_resolved' => 'boolean',
    ];
}