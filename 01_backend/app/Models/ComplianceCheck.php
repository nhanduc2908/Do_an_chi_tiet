<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceCheck extends Model
{
    protected $fillable = [
        'standard', 'status', 'score', 'findings', 'recommendations',
        'checked_by', 'checked_at', 'next_check_date',
    ];

    protected $casts = [
        'score' => 'float',
        'findings' => 'array',
        'recommendations' => 'array',
        'checked_at' => 'datetime',
        'next_check_date' => 'date',
    ];

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}