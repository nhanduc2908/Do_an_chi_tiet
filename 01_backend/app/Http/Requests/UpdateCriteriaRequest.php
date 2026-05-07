<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'weight' => 'nullable|numeric|min:0|max:100',
            'priority' => 'nullable|in:high,medium,low',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}