<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'display_name' => 'nullable|string',
            'level' => 'nullable|integer|min:0|max:100',
            'color' => 'nullable|string',
        ];
    }
}