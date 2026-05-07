<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'vendor_id', 'contract_number', 'title', 'start_date',
        'end_date', 'value', 'status', 'file_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'value' => 'float',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}