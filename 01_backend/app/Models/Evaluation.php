<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'title', 'domain_id', 'company_id', 'user_id',
        'notes', 'status', 'total_score', 'max_score', 'percentage',
        'submitted_at', 'approved_at', 'approved_by', 'rejected_at',
        'rejected_by', 'rejection_reason', 'approver_note', 'ai_score',
    ];

    protected $casts = [
        'total_score' => 'float',
        'max_score' => 'float',
        'percentage' => 'float',
        'ai_score' => 'float',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(EvaluationDetail::class);
    }

    public function history()
    {
        return $this->hasMany(EvaluationHistory::class);
    }
}