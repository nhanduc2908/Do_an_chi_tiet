<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|unique:companies,name,' . $this->route('id'),
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}