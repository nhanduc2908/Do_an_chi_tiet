<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => 'required|string|max:255',
            'device_name' => 'required|string|max:255',
            'device_type' => 'required|string|in:android,ios,web,desktop',
            'push_token' => 'nullable|string',
        ];
    }
}