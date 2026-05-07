<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'name', 'domain', 'certificate_path', 'private_key_path',
        'expires_at', 'created_by', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expires_at && $this->expires_at->diffInDays(now()) <= $days;
    }
}