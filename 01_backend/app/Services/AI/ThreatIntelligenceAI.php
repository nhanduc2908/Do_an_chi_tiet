<?php

namespace App\Services\AI;

use App\Models\Company;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ThreatIntelligenceAI
{
    protected string $aiApiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->aiApiUrl = config('ai.api_url', 'http://ai-service:5000');
        $this->apiKey = config('ai.api_key');
    }

    public function analyzeThreats(Company $company): array
    {
        $industry = $company->industry ?? 'general';
        $size = $company->size ?? 'medium';
        
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
            ->post($this->aiApiUrl . '/api/v1/threats/analyze', [
                'industry' => $industry,
                'size' => $size,
                'location' => $company->country ?? 'Vietnam',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $this->getDefaultThreats($industry);
    }

    public function updateThreatIntel(): array
    {
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
            ->get($this->aiApiUrl . '/api/v1/threats/update');

        if ($response->successful()) {
            Cache::put('threat_intel_data', $response->json(), 3600);
            return $response->json();
        }

        return Cache::get('threat_intel_data', []);
    }

    public function searchIoC(string $indicator): array
    {
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
            ->get($this->aiApiUrl . '/api/v1/ioc/search', [
                'indicator' => $indicator,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return ['found' => false, 'reputation' => 'unknown'];
    }

    public function analyzeCampaign(array $events): array
    {
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
            ->post($this->aiApiUrl . '/api/v1/campaign/analyze', [
                'events' => $events,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'is_campaign' => false,
            'confidence' => 0,
            'pattern' => 'unknown',
        ];
    }

    protected function getDefaultThreats(string $industry): array
    {
        $threats = [
            'finance' => [
                ['name' => 'Phishing', 'severity' => 'high', 'likelihood' => 0.7],
                ['name' => 'Ransomware', 'severity' => 'critical', 'likelihood' => 0.5],
                ['name' => 'DDoS', 'severity' => 'medium', 'likelihood' => 0.4],
            ],
            'technology' => [
                ['name' => 'Data Breach', 'severity' => 'high', 'likelihood' => 0.6],
                ['name' => 'Supply Chain Attack', 'severity' => 'high', 'likelihood' => 0.4],
                ['name' => 'Insider Threat', 'severity' => 'medium', 'likelihood' => 0.5],
            ],
            'healthcare' => [
                ['name' => 'Ransomware', 'severity' => 'critical', 'likelihood' => 0.6],
                ['name' => 'Data Breach', 'severity' => 'high', 'likelihood' => 0.5],
                ['name' => 'IoT Attack', 'severity' => 'medium', 'likelihood' => 0.3],
            ],
        ];
        
        return $threats[$industry] ?? [
            ['name' => 'Phishing', 'severity' => 'medium', 'likelihood' => 0.5],
            ['name' => 'Malware', 'severity' => 'medium', 'likelihood' => 0.4],
            ['name' => 'Brute Force', 'severity' => 'low', 'likelihood' => 0.3],
        ];
    }
}