<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:evaluation_summary,security_score,trend_analysis,compliance,comparison',
            'format' => 'required|string|in:pdf,excel,csv,json',
            'domain_id' => 'nullable|exists:domains,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ];
    }
}