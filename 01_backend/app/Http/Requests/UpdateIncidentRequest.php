<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'secops']);
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|in:open,investigating,contained,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}