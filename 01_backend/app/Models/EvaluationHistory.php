<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationHistory extends Model
{
    protected $fillable = [
        'evaluation_id', 'user_id', 'action', 'old_data', 'new_data', 'ip_address',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}