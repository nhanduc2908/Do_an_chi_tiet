<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanResult extends Model
{
    protected $fillable = [
        'type', 'target', 'status', 'result', 'vulnerabilities',
        'user_id', 'started_at', 'completed_at', 'summary',
    ];

    protected $casts = [
        'result' => 'array',
        'vulnerabilities' => 'array',
        'summary' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vulnerabilitiesList()
    {
        return $this->hasMany(Vulnerability::class, 'scan_result_id');
    }
}