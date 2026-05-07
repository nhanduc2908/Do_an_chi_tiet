<?php

namespace App\Services\Version;

class VersionManager
{
    protected array $versions = ['v1', 'v2', 'v3', 'v4', 'v5'];

    public function getAll(): array 
    { 
        return $this->versions; 
    }

    public function getCurrent(): string 
    { 
        return session('version', 'v1'); 
    }

    public function setCurrent(string $version): void 
    { 
        if (in_array($version, $this->versions)) session(['version' => $version]); 
    }

    public function getLimit(string $version): int 
    { 
        return match($version) { 
            'v1' => 10, 'v2' => 50, 'v3' => 200, 'v4' => 500, 'v5' => 9999, default => 10 
        }; 
    }

    public function getFeatures(string $version): array
    {
        return match($version) {
            'v1' => ['basic_evaluation', 'basic_report'],
            'v2' => ['advanced_scoring', 'team_evaluation'],
            'v3' => ['real_time_sync', 'ai_suggestions'],
            'v4' => ['compliance_check', 'risk_matrix'],
            'v5' => ['dark_web_monitor', 'threat_intel'],
            default => [],
        };
    }
}