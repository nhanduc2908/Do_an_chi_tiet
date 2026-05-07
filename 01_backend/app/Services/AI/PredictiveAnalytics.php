<?php

namespace App\Services\AI;

use App\Models\Evaluation;
use App\Models\Company;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PredictiveAnalytics
{
    protected string $aiApiUrl;

    public function __construct()
    {
        $this->aiApiUrl = config('ai.api_url', 'http://ai-service:5000');
    }

    public function predictSecurityTrend(Company $company, int $months = 12): array
    {
        $historicalData = Evaluation::where('company_id', $company->id)
            ->orderBy('created_at')
            ->get(['percentage', 'created_at']);

        $response = Http::post($this->aiApiUrl . '/api/v1/trend/predict', [
            'historical_data' => $historicalData,
            'months' => $months,
            'industry' => $company->industry,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $this->simpleMovingAverage($historicalData, $months);
    }

    public function predictBreachProbability(Company $company): array
    {
        $lastEvaluation = Evaluation::where('company_id', $company->id)
            ->latest()
            ->first();

        if (!$lastEvaluation) {
            return ['probability' => 0.5, 'risk_level' => 'medium'];
        }

        $score = $lastEvaluation->percentage;
        
        if ($score >= 90) return ['probability' => 0.05, 'risk_level' => 'very_low'];
        if ($score >= 75) return ['probability' => 0.10, 'risk_level' => 'low'];
        if ($score >= 60) return ['probability' => 0.25, 'risk_level' => 'medium'];
        if ($score >= 40) return ['probability' => 0.50, 'risk_level' => 'high'];
        return ['probability' => 0.75, 'risk_level' => 'very_high'];
    }

    public function predictBudgetRequired(Company $company): array
    {
        $currentScore = Evaluation::where('company_id', $company->id)
            ->latest()
            ->value('percentage') ?? 50;

        $targetScore = 85;
        $gap = max(0, $targetScore - $currentScore);
        $estimatedBudget = $gap * ($company->size_factor ?? 1) * 1000;

        return [
            'current_score' => $currentScore,
            'target_score' => $targetScore,
            'gap' => $gap,
            'estimated_budget' => $estimatedBudget,
            'currency' => 'USD',
            'recommended_allocations' => [
                'technology' => $estimatedBudget * 0.4,
                'training' => $estimatedBudget * 0.3,
                'consulting' => $estimatedBudget * 0.2,
                'other' => $estimatedBudget * 0.1,
            ],
        ];
    }

    protected function simpleMovingAverage($historicalData, int $months): array
    {
        $scores = $historicalData->pluck('percentage')->toArray();
        $window = min(3, count($scores));
        
        if (count($scores) < $window) {
            return ['predictions' => array_fill(0, $months, end($scores) ?? 50)];
        }

        $movingAverages = [];
        for ($i = $window - 1; $i < count($scores); $i++) {
            $slice = array_slice($scores, $i - $window + 1, $window);
            $movingAverages[] = array_sum($slice) / $window;
        }

        $lastAvg = end($movingAverages);
        $predictions = array_fill(0, $months, $lastAvg);
        
        return ['predictions' => $predictions, 'method' => 'simple_moving_average'];
    }
}