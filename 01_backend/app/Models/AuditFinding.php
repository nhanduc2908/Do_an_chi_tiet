<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditFinding extends Model
{
    protected $fillable = [
        'audit_id', 'title', 'description', 'severity',
        'recommendation', 'status', 'assigned_to',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}