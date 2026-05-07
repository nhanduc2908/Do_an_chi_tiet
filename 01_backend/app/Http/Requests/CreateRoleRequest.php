<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles|regex:/^[a-z_]+$/',
            'display_name' => 'required|string',
            'level' => 'required|integer|min:0|max:100',
            'color' => 'nullable|string',
        ];
    }
}