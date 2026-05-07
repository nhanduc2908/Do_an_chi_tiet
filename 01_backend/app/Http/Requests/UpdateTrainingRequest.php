<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager', 'hr']);
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string',
            'duration' => 'nullable|integer',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}