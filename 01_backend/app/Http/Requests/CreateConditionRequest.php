<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateConditionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'criteria_id' => 'required|exists:criteria,id',
            'weight' => 'nullable|numeric|min:0|max:100',
        ];
    }
}