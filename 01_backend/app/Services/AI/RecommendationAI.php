<?php

namespace App\Services\AI;

use App\Models\Evaluation;
use App\Models\Domain;
use App\Models\Criteria;
use Illuminate\Support\Facades\Http;

class RecommendationAI
{
    protected string $aiApiUrl;

    public function __construct()
    {
        $this->aiApiUrl = config('ai.api_url', 'http://ai-service:5000');
    }

    public function generateRecommendations(Evaluation $evaluation): array
    {
        $weaknesses = $this->identifyWeaknesses($evaluation);
        
        $response = Http::post($this->aiApiUrl . '/api/v1/recommendations', [
            'evaluation_id' => $evaluation->id,
            'weaknesses' => $weaknesses,
            'industry' => $evaluation->company->industry ?? 'general',
        ]);

        if ($response->successful()) {
            return $this->prioritizeActions($response->json());
        }

        return $this->generateBasicRecommendations($weaknesses);
    }

    public function prioritizeSecurityMeasures(Evaluation $evaluation): array
    {
        $lowScores = $evaluation->details()
            ->with('criteria')
            ->where('percentage', '<', 60)
            ->get();

        $measures = [];
        foreach ($lowScores as $detail) {
            $measures[] = [
                'criteria' => $detail->criteria->name,
                'domain' => $detail->criteria->domain->name,
                'current_score' => $detail->percentage,
                'recommended_action' => $this->getRecommendedAction($detail->criteria),
                'estimated_effort' => $this->estimateEffort($detail->criteria),
                'estimated_impact' => $this->estimateImpact($detail->criteria),
            ];
        }

        usort($measures, fn($a, $b) => $b['estimated_impact'] - $a['estimated_impact']);
        
        return $measures;
    }

    protected function identifyWeaknesses(Evaluation $evaluation): array
    {
        $weaknesses = [];
        foreach ($evaluation->details as $detail) {
            if ($detail->percentage < 60) {
                $weaknesses[] = [
                    'criteria_id' => $detail->criteria_id,
                    'criteria_name' => $detail->criteria->name,
                    'domain' => $detail->criteria->domain->name,
                    'current_score' => $detail->percentage,
                    'gap' => 100 - $detail->percentage,
                ];
            }
        }
        return $weaknesses;
    }

    protected function prioritizeActions(array $recommendations): array
    {
        usort($recommendations, function ($a, $b) {
            return $b['impact_score'] - $a['impact_score'];
        });
        
        foreach ($recommendations as &$rec) {
            $rec['priority'] = $this->calculatePriority($rec);
        }
        
        return $recommendations;
    }

    protected function calculatePriority(array $recommendation): string
    {
        $score = $recommendation['impact_score'] * ($recommendation['effort_score'] ?? 1);
        if ($score > 80) return 'critical';
        if ($score > 60) return 'high';
        if ($score > 40) return 'medium';
        return 'low';
    }

    protected function getRecommendedAction($criteria): string
    {
        $actions = [
            'access_control' => 'Triển khai MFA và RBAC',
            'encryption' => 'Mã hóa dữ liệu ở trạng thái nghỉ và truyền tải',
            'monitoring' => 'Thiết lập hệ thống giám sát 24/7',
            'training' => 'Đào tạo nhận thức an toàn cho nhân viên',
            'backup' => 'Thiết lập chiến lược sao lưu và phục hồi',
        ];
        return $actions[$criteria->type] ?? 'Đánh giá và cải thiện theo tiêu chuẩn';
    }

    protected function estimateEffort($criteria): int
    {
        $efforts = ['low' => 10, 'medium' => 30, 'high' => 70];
        return $efforts[$criteria->priority ?? 'medium'] ?? 30;
    }

    protected function estimateImpact($criteria): int
    {
        $impacts = ['low' => 20, 'medium' => 50, 'high' => 80];
        return $impacts[$criteria->priority ?? 'medium'] ?? 50;
    }

    protected function generateBasicRecommendations(array $weaknesses): array
    {
        $recommendations = [];
        foreach ($weaknesses as $weakness) {
            $recommendations[] = [
                'title' => "Cải thiện: {$weakness['criteria_name']}",
                'description' => "Điểm hiện tại: {$weakness['current_score']}%. Cần cải thiện thêm {$weakness['gap']}%.",
                'priority' => $weakness['gap'] > 30 ? 'high' : 'medium',
            ];
        }
        return $recommendations;
    }
}