<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->role === 'admin' || $this->user()->id == $this->route('id'));
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->route('id'),
            'role' => 'nullable|string|in:admin,dev,ba,da,hr,qa,secops,auditor,manager,ciso',
            'company_id' => 'nullable|exists:companies,id',
            'avatar' => 'nullable|image|max:2048',
        ];
    }
}