<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => 'required|string|min:2',
            'type' => 'nullable|string|in:evaluation,report,user,company,all',
            'limit' => 'nullable|integer|min:1|max:100',
        ];
    }
}