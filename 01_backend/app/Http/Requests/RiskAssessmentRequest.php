<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiskAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'asset_id' => 'required|exists:assets,id',
            'threat' => 'required|string',
            'vulnerability' => 'required|string',
            'likelihood' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
        ];
    }
}