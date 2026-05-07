<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'cron' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'format' => 'required|in:pdf,excel,csv',
        ];
    }
}