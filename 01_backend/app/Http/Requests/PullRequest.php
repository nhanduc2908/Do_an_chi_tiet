<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PullRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'last_sync' => 'nullable|date',
            'device_id' => 'required|string',
            'limit' => 'nullable|integer|min:1|max:1000',
        ];
    }
}