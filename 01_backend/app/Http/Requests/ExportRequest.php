<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'format' => 'required|string|in:pdf,excel,csv,json',
            'type' => 'required|string|in:evaluation,report,criteria',
            'ids' => 'nullable|array',
        ];
    }
}