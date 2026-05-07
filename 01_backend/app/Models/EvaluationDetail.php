<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationDetail extends Model
{
    protected $fillable = [
        'evaluation_id', 'criteria_id', 'score', 'percentage', 'notes',
    ];

    protected $casts = [
        'score' => 'float',
        'percentage' => 'float',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function conditions()
    {
        return $this->belongsToMany(Condition::class, 'evaluation_detail_condition')
            ->withPivot('status', 'met_at');
    }
}