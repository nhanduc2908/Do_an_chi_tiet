<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'domain' => 'required|string|unique:certificates',
            'certificate_file' => 'required|file|mimes:pem,crt',
            'private_key_file' => 'required|file|mimes:key,pem',
        ];
    }
}