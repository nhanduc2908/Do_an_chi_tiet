<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'risk_level' => 'nullable|in:low,medium,high,critical',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}