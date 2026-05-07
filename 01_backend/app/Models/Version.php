<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $fillable = [
        'name', 'code', 'description', 'features', 'max_users',
        'max_evaluations', 'price', 'is_active', 'release_date',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'release_date' => 'date',
        'price' => 'float',
    ];

    public const V1_SME = 'v1_sme';
    public const V2_MIDMARKET = 'v2_midmarket';
    public const V3_ENTERPRISE = 'v3_enterprise';
    public const V4_FINANCE = 'v4_finance';
    public const V5_GOVERNMENT = 'v5_government';
}