<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = [
        'url', 'secret', 'events', 'is_active', 'last_triggered_at',
        'last_response_code', 'failure_count',
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
        'failure_count' => 'integer',
    ];

    public function deliveries()
    {
        return $this->hasMany(WebhookDelivery::class);
    }
}