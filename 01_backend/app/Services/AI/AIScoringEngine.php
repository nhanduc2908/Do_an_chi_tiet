<?php

namespace App\Services\AI;

use App\Models\Evaluation;
use App\Models\Criteria;
use App\Models\Domain;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AIScoringEngine
{
    protected string $aiApiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->aiApiUrl = config('ai.api_url', 'http://ai-service:5000');
        $this->apiKey = config('ai.api_key');
    }

    public function autoScore(Evaluation $evaluation): array
    {
        $data = $this->collectEvaluationData($evaluation);
        
        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->aiApiUrl . '/api/v1/score', [
            'evaluation_id' => $evaluation->id,
            'criteria_data' => $data['criteria'],
            'historical_data' => $data['historical'],
            'industry_benchmark' => $data['benchmark'],
        ]);

        if ($response->successful()) {
            $scores = $response->json();
            $this->saveScores($evaluation, $scores);
            return $scores;
        }

        return $this->ruleBasedScoring($evaluation);
    }

    public function predictScore(Evaluation $evaluation, int $days = 90): array
    {
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
            ->post($this->aiApiUrl . '/api/v1/predict', [
                'evaluation_id' => $evaluation->id,
                'days' => $days,
            ]);

        return $response->json() ?? [
            'predicted_score' => $evaluation->percentage,
            'confidence' => 0,
            'trend' => 'stable',
        ];
    }

    public function detectAnomalies(Evaluation $evaluation): array
    {
        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
            ->post($this->aiApiUrl . '/api/v1/anomalies', [
                'evaluation_id' => $evaluation->id,
                'scores' => $evaluation->details->pluck('score')->toArray(),
            ]);

        return $response->json() ?? [];
    }

    protected function collectEvaluationData(Evaluation $evaluation): array
    {
        $criteria = Criteria::with('domain')->get();
        $criteriaData = [];

        foreach ($criteria as $criterion) {
            $detail = $evaluation->details->where('criteria_id', $criterion->id)->first();
            $criteriaData[] = [
                'id' => $criterion->id,
                'domain_id' => $criterion->domain_id,
                'score' => $detail->score ?? 0,
                'weight' => $criterion->weight,
                'domain_weight' => $criterion->domain->weight ?? 1,
            ];
        }

        return [
            'criteria' => $criteriaData,
            'historical' => $this->getHistoricalData($evaluation->company_id),
            'benchmark' => $this->getIndustryBenchmark($evaluation->company->industry ?? 'general'),
        ];
    }

    protected function ruleBasedScoring(Evaluation $evaluation): array
    {
        $totalScore = 0;
        $totalWeight = 0;

        foreach ($evaluation->details as $detail) {
            $criteria = $detail->criteria;
            $metConditions = $detail->conditions->where('status', 'met')->count();
            $totalConditions = $criteria->conditions->count();
            $score = $totalConditions > 0 ? ($metConditions / $totalConditions) * $criteria->weight : 0;
            $totalScore += $score;
            $totalWeight += $criteria->weight;
        }

        $percentage = $totalWeight > 0 ? ($totalScore / $totalWeight) * 100 : 0;
        
        return [
            'total_score' => round($totalScore, 2),
            'percentage' => round($percentage, 2),
            'by_domain' => $this->calculateDomainScores($evaluation),
        ];
    }

    protected function saveScores(Evaluation $evaluation, array $scores): void
    {
        foreach ($scores['criteria_scores'] as $criteriaId => $score) {
            $detail = $evaluation->details->where('criteria_id', $criteriaId)->first();
            if ($detail) {
                $detail->update(['score' => $score, 'percentage' => $score]);
            }
        }
        $evaluation->update([
            'total_score' => $scores['total_score'],
            'percentage' => $scores['percentage'],
            'ai_score' => $scores['percentage'],
        ]);
    }

    protected function getHistoricalData($companyId): array
    {
        return Cache::remember("historical_data_{$companyId}", 3600, function () use ($companyId) {
            return Evaluation::where('company_id', $companyId)
                ->orderBy('created_at')
                ->pluck('percentage')
                ->toArray();
        });
    }

    protected function getIndustryBenchmark(string $industry): array
    {
        $benchmarks = [
            'finance' => ['avg' => 85, 'max' => 98, 'min' => 45],
            'healthcare' => ['avg' => 78, 'max' => 95, 'min' => 40],
            'technology' => ['avg' => 82, 'max' => 97, 'min' => 50],
            'retail' => ['avg' => 70, 'max' => 90, 'min' => 35],
            'default' => ['avg' => 75, 'max' => 92, 'min' => 42],
        ];
        return $benchmarks[$industry] ?? $benchmarks['default'];
    }

    protected function calculateDomainScores(Evaluation $evaluation): array
    {
        $domains = Domain::with('criteria')->get();
        $scores = [];
        foreach ($domains as $domain) {
            $domainScore = 0;
            $domainWeight = 0;
            foreach ($domain->criteria as $criteria) {
                $detail = $evaluation->details->where('criteria_id', $criteria->id)->first();
                if ($detail) {
                    $domainScore += $detail->score;
                    $domainWeight += $criteria->weight;
                }
            }
            $scores[$domain->id] = $domainWeight > 0 ? round(($domainScore / $domainWeight) * 100, 2) : 0;
        }
        return $scores;
    }
}