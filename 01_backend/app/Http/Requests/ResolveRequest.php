<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'conflict_id' => 'required|string',
            'resolution' => 'required|in:server,client,manual',
            'data' => 'nullable|array',
        ];
    }
}