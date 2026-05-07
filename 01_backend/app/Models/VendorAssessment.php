<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorAssessment extends Model
{
    protected $fillable = [
        'vendor_id', 'assessor_id', 'score', 'findings',
        'recommendations', 'assessed_at', 'next_assessment_date',
    ];

    protected $casts = [
        'score' => 'float',
        'findings' => 'array',
        'assessed_at' => 'datetime',
        'next_assessment_date' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }
}