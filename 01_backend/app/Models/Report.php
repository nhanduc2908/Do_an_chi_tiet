<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title', 'type', 'format', 'evaluation_id', 'user_id',
        'file_path', 'file_size', 'status', 'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'file_size' => 'integer',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}