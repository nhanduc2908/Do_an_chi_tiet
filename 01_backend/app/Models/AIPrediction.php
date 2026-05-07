<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIPrediction extends Model
{
    protected $fillable = [
        'evaluation_id', 'model_id', 'predicted_score', 'confidence',
        'trend', 'data', 'is_used',
    ];

    protected $casts = [
        'predicted_score' => 'float',
        'confidence' => 'float',
        'data' => 'array',
        'is_used' => 'boolean',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function model()
    {
        return $this->belongsTo(AIModel::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(AIFeedback::class);
    }
}