<?php

namespace App\Services\Evaluation;

use App\Models\Evaluation;

class RecommendationEngine
{
    public function generate(Evaluation $evaluation): array
    {
        $recommendations = [];
        foreach ($evaluation->details as $detail) {
            if ($detail->percentage < 60) {
                $recommendations[] = [
                    'criteria' => $detail->criteria->name,
                    'current_score' => $detail->percentage,
                    'suggestion' => $this->getSuggestion($detail->criteria->name),
                ];
            }
        }
        return $recommendations;
    }

    protected function getSuggestion(string $criteriaName): string
    {
        return "Cần cải thiện tiêu chí '{$criteriaName}' theo khuyến nghị.";
    }
}