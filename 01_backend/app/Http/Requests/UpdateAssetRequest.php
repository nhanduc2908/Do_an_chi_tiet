<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'classification' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}