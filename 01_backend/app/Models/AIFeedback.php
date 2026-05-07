<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIFeedback extends Model
{
    protected $fillable = [
        'prediction_id', 'user_id', 'is_accurate', 'actual_score',
        'feedback', 'used_for_retraining',
    ];

    protected $casts = [
        'is_accurate' => 'boolean',
        'actual_score' => 'float',
        'used_for_retraining' => 'boolean',
    ];

    public function prediction()
    {
        return $this->belongsTo(AIPrediction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}