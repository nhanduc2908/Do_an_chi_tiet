<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityViolation extends Model
{
    protected $fillable = [
        'user_id', 'type', 'severity', 'description', 'ip_address',
        'user_agent', 'resolved_at', 'resolved_by', 'resolution_note',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}