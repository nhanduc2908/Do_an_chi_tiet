<?php

namespace App\Services\AI;

use App\Models\Evaluation;
use App\Models\LoginHistory;
use App\Models\SecurityViolation;
use Illuminate\Support\Facades\Http;

class AnomalyDetector
{
    protected string $aiApiUrl;

    public function __construct()
    {
        $this->aiApiUrl = config('ai.api_url', 'http://ai-service:5000');
    }

    public function detectEvaluationAnomalies(Evaluation $evaluation): array
    {
        $details = $evaluation->details;
        $scores = $details->pluck('score')->toArray();
        
        $mean = array_sum($scores) / count($scores);
        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $scores)) / count($scores);
        $stdDev = sqrt($variance);
        
        $anomalies = [];
        foreach ($details as $detail) {
            $zScore = ($detail->score - $mean) / ($stdDev ?: 1);
            if (abs($zScore) > 2) {
                $anomalies[] = [
                    'criteria_id' => $detail->criteria_id,
                    'criteria_name' => $detail->criteria->name,
                    'score' => $detail->score,
                    'z_score' => round($zScore, 2),
                    'severity' => abs($zScore) > 3 ? 'high' : 'medium',
                ];
            }
        }
        
        return ['anomalies' => $anomalies, 'total_anomalies' => count($anomalies)];
    }

    public function detectLoginAnomalies(int $userId): array
    {
        $recentLogins = LoginHistory::where('user_id', $userId)
            ->orderBy('login_at', 'desc')
            ->limit(10)
            ->get();

        $ips = $recentLogins->pluck('ip_address')->unique();
        $locations = $recentLogins->pluck('location')->filter()->unique();
        
        $anomalies = [];
        
        if ($ips->count() > 3) {
            $anomalies[] = ['type' => 'multiple_ips', 'count' => $ips->count()];
        }
        
        if ($locations->count() > 2) {
            $anomalies[] = ['type' => 'multiple_locations', 'count' => $locations->count()];
        }

        $unusualHours = $recentLogins->filter(function($login) {
            $hour = $login->login_at->hour;
            return $hour < 6 || $hour > 22;
        });

        if ($unusualHours->count() > 3) {
            $anomalies[] = ['type' => 'unusual_hours', 'count' => $unusualHours->count()];
        }

        return ['anomalies' => $anomalies, 'risk_score' => count($anomalies) * 33];
    }

    public function detectDataAnomalies(string $tableName, array $data): array
    {
        $response = Http::post($this->aiApiUrl . '/api/v1/anomalies/detect', [
            'table' => $tableName,
            'data' => $data,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $this->statisticalAnomalyDetection($data);
    }

    protected function statisticalAnomalyDetection(array $data): array
    {
        $values = array_column($data, 'value');
        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $values)) / count($values);
        $stdDev = sqrt($variance);
        
        $anomalies = [];
        foreach ($data as $index => $item) {
            $zScore = ($item['value'] - $mean) / ($stdDev ?: 1);
            if (abs($zScore) > 2.5) {
                $anomalies[] = [
                    'index' => $index,
                    'value' => $item['value'],
                    'z_score' => round($zScore, 2),
                ];
            }
        }
        
        return ['anomalies' => $anomalies, 'method' => 'statistical'];
    }
}