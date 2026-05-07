<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'secops']);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'severity' => 'required|in:critical,high,medium,low',
            'type' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}