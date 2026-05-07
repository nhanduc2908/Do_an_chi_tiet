<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['dev', 'qa', 'secops', 'admin']);
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:password,web,code,database,network,api,mobile',
            'target' => 'required|string',
            'options' => 'nullable|array',
        ];
    }
}