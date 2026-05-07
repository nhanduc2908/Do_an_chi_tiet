<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.criteria_id' => 'required|exists:criteria,id',
            'items.*.score' => 'nullable|numeric|min:0|max:100',
            'items.*.notes' => 'nullable|string',
            'items.*.condition_ids' => 'nullable|array',
            'items.*.condition_ids.*' => 'exists:conditions,id',
        ];
    }
}