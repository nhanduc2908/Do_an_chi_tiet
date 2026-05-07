<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingAssignment extends Model
{
    protected $fillable = [
        'training_id', 'user_id', 'assigned_by', 'status',
        'completed_at', 'score', 'feedback',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'score' => 'float',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}