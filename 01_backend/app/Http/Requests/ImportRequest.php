<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,csv,xls',
            'domain_id' => 'required|exists:domains,id',
            'type' => 'nullable|string|in:evaluation,criteria',
        ];
    }
}