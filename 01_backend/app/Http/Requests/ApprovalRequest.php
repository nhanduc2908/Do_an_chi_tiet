<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager', 'ciso']);
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected',
            'note' => 'required_if:status,rejected|nullable|string',
        ];
    }
}