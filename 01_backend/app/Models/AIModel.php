<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIModel extends Model
{
    protected $table = 'ai_models';

    protected $fillable = [
        'name', 'type', 'version', 'path', 'accuracy',
        'precision', 'recall', 'f1_score', 'status',
        'trained_at', 'training_data_size',
    ];

    protected $casts = [
        'accuracy' => 'float',
        'precision' => 'float',
        'recall' => 'float',
        'f1_score' => 'float',
        'trained_at' => 'datetime',
        'training_data_size' => 'integer',
    ];

    public function predictions()
    {
        return $this->hasMany(AIPrediction::class);
    }
}