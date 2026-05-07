<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name', 'code', 'department_id', 'lead_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function lead()
    {
        return $this->belongsTo(User::class, 'lead_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}