<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkOperationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'action' => 'required|string|in:delete,approve,reject,export',
        ];
    }
}