<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataChangeLog extends Model
{
    protected $fillable = [
        'user_id', 'table_name', 'record_id', 'action', 'old_data',
        'new_data', 'changes', 'ip_address',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}