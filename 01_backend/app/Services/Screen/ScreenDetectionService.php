<?php

namespace App\Services\Screen;

class ScreenDetectionService
{
    public function detect(array $screenData): array
    {
        $width = $screenData['width'] ?? 1920;
        
        $device = match(true) {
            $width < 768 => 'mobile',
            $width < 1024 => 'tablet',
            $width < 1440 => 'desktop',
            default => 'large',
        };
        
        $layout = match($device) {
            'mobile' => 'single_column',
            'tablet' => 'two_column',
            'desktop' => 'three_column',
            'large' => 'four_column',
        };
        
        return ['device' => $device, 'layout' => $layout];
    }
}