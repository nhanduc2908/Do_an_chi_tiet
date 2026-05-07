<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'domain_id' => 'required|exists:domains,id',
            'company_id' => 'nullable|exists:companies,id',
            'notes' => 'nullable|string',
        ];
    }
}