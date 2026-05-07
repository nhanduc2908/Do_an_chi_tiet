<?php

namespace App\Services\AI;

use App\Models\Evaluation;
use App\Models\Company;
use Illuminate\Support\Facades\Http;

class RiskClassifier
{
    protected string $aiApiUrl;

    public function __construct()
    {
        $this->aiApiUrl = config('ai.api_url', 'http://ai-service:5000');
    }

    public function classifyCompanyRisk(Company $company): array
    {
        $lastEvaluation = Evaluation::where('company_id', $company->id)->latest()->first();
        
        if (!$lastEvaluation) {
            return ['risk_level' => 'unknown', 'score' => 0];
        }

        $score = $lastEvaluation->percentage;
        
        if ($score >= 90) return ['risk_level' => 'very_low', 'score' => $score, 'color' => '#00FF00'];
        if ($score >= 75) return ['risk_level' => 'low', 'score' => $score, 'color' => '#90EE90'];
        if ($score >= 60) return ['risk_level' => 'medium', 'score' => $score, 'color' => '#FFD700'];
        if ($score >= 40) return ['risk_level' => 'high', 'score' => $score, 'color' => '#FFA500'];
        return ['risk_level' => 'critical', 'score' => $score, 'color' => '#FF0000'];
    }

    public function classifyVulnerability(array $vulnerability): array
    {
        $cvssScore = $vulnerability['cvss_score'] ?? 0;
        
        if ($cvssScore >= 9.0) return ['severity' => 'critical', 'priority' => 1];
        if ($cvssScore >= 7.0) return ['severity' => 'high', 'priority' => 2];
        if ($cvssScore >= 4.0) return ['severity' => 'medium', 'priority' => 3];
        if ($cvssScore > 0) return ['severity' => 'low', 'priority' => 4];
        return ['severity' => 'info', 'priority' => 5];
    }

    public function predictAttackLikelihood(Evaluation $evaluation): array
    {
        $score = $evaluation->percentage;
        $domainScores = $evaluation->details->groupBy('criteria.domain_id')
            ->map(fn($items) => $items->avg('percentage'));
        
        $criticalDomains = $domainScores->filter(fn($s) => $s < 50)->keys();
        
        $likelihood = match(true) {
            $score < 40 => 0.8,
            $score < 60 => 0.5,
            $score < 80 => 0.2,
            default => 0.05,
        };

        return [
            'likelihood' => $likelihood,
            'percentage' => $likelihood * 100,
            'level' => $this->getLikelihoodLevel($likelihood),
            'critical_domains' => $criticalDomains->values(),
        ];
    }

    protected function getLikelihoodLevel(float $likelihood): string
    {
        if ($likelihood >= 0.7) return 'very_high';
        if ($likelihood >= 0.5) return 'high';
        if ($likelihood >= 0.3) return 'medium';
        if ($likelihood >= 0.1) return 'low';
        return 'very_low';
    }
}