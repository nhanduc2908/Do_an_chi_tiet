<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:vendors',
            'contact_name' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'risk_level' => 'nullable|in:low,medium,high,critical',
        ];
    }
}