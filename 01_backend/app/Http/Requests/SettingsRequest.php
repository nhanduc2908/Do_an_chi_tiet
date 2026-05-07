<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'app_name' => 'nullable|string',
            'maintenance_mode' => 'boolean',
            'session_timeout' => 'nullable|integer|min:5|max:1440',
            'max_login_attempts' => 'nullable|integer|min:1|max:10',
        ];
    }
}
