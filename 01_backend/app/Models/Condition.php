<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $fillable = [
        'name', 'description', 'criteria_id', 'weight', 'order',
    ];

    protected $casts = [
        'weight' => 'float',
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function evaluationDetails()
    {
        return $this->belongsToMany(EvaluationDetail::class, 'evaluation_detail_condition');
    }
}