<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskTreatment extends Model
{
    protected $fillable = [
        'risk_assessment_id', 'action', 'description', 'due_date',
        'assigned_to', 'status', 'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}