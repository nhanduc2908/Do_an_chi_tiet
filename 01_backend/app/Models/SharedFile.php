<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedFile extends Model
{
    protected $fillable = [
        'file_id', 'shared_by', 'shared_with', 'permission',
        'share_token', 'expires_at', 'accessed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accessed_at' => 'datetime',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function sharedWith()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }

    public function isValid()
    {
        return is_null($this->expires_at) || $this->expires_at->isFuture();
    }
}