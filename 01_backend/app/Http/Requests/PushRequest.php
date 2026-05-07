<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PushRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'device_id' => 'required|string',
            'version_vector' => 'nullable|array',
        ];
    }
}