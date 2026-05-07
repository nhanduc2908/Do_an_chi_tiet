<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'name_en' => 'required|string',
            'code' => 'required|string|unique:domains',
            'weight' => 'nullable|numeric|min:0|max:100',
        ];
    }
}