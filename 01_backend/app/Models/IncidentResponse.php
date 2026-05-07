<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentResponse extends Model
{
    protected $fillable = [
        'incident_id', 'action', 'description', 'performed_by', 'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}