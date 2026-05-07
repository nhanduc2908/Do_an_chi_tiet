<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $fillable = [
        'name', 'name_en', 'code', 'domain_id', 'criteria_group',
        'weight', 'priority', 'description', 'order', 'status',
    ];

    protected $casts = [
        'weight' => 'float',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function conditions()
    {
        return $this->hasMany(Condition::class);
    }

    public function evaluationDetails()
    {
        return $this->hasMany(EvaluationDetail::class);
    }
}