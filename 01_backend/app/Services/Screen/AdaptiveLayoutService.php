<?php

namespace App\Services\Screen;

class AdaptiveLayoutService
{
    public function getLayout(string $deviceType): string
    {
        return match($deviceType) {
            'mobile' => 'layouts.mobile',
            'tablet' => 'layouts.tablet',
            'desktop' => 'layouts.desktop',
            'large' => 'layouts.large',
            default => 'layouts.default',
        };
    }
}