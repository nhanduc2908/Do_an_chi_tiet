<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplianceCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['admin', 'auditor']);
    }

    public function rules(): array
    {
        return [
            'standard' => 'required|string|in:iso27001,soc2,gdpr,hipaa,pci_dss',
        ];
    }
}