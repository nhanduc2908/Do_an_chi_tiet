<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'name', 'name_en', 'code', 'description', 'weight', 'status',
    ];

    protected $casts = [
        'weight' => 'float',
    ];

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}