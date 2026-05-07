<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name', 'type', 'classification', 'owner_id',
        'location', 'value', 'status', 'description',
    ];

    protected $casts = [
        'value' => 'float',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class);
    }
}