<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'weight' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}