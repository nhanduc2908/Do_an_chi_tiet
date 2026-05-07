<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScreenSyncRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'screen_width' => 'required|integer|min:320',
            'screen_height' => 'required|integer|min:480',
            'device_pixel_ratio' => 'nullable|numeric|min:0.5|max:5',
            'orientation' => 'nullable|string|in:portrait,landscape',
        ];
    }
}