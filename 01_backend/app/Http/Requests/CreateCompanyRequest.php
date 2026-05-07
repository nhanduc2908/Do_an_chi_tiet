<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:companies',
            'code' => 'required|string|unique:companies',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ];
    }
}