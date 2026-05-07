<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookDelivery extends Model
{
    protected $fillable = [
        'webhook_id', 'event', 'payload', 'response_code',
        'response_body', 'duration', 'status', 'attempt',
    ];

    protected $casts = [
        'payload' => 'array',
        'duration' => 'integer',
        'attempt' => 'integer',
    ];

    public function webhook()
    {
        return $this->belongsTo(Webhook::class);
    }
}