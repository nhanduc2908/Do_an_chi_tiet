<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name', 'contact_name', 'contact_email', 'contact_phone',
        'website', 'risk_level', 'status', 'description',
    ];

    public function assessments()
    {
        return $this->hasMany(VendorAssessment::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}