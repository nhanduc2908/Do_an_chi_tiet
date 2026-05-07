<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskAssessment extends Model
{
    protected $fillable = [
        'asset_id', 'threat', 'vulnerability', 'likelihood',
        'impact', 'risk_score', 'risk_level', 'mitigation',
        'owner_id', 'assessed_by', 'assessed_at',
    ];

    protected $casts = [
        'likelihood' => 'integer',
        'impact' => 'integer',
        'risk_score' => 'float',
        'assessed_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function treatments()
    {
        return $this->hasMany(RiskTreatment::class);
    }
}