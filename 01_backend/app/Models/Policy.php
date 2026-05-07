<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'name', 'content', 'category', 'version', 'effective_date',
        'created_by', 'is_active',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function acknowledgments()
    {
        return $this->hasMany(PolicyAcknowledgment::class);
    }
}