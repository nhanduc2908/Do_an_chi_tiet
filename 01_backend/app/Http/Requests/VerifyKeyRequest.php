<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyKeyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'signature' => 'required|string',
            'challenge' => 'required|string',
            'device_id' => 'required|string',
            'session_token' => 'required|string',
        ];
    }
}