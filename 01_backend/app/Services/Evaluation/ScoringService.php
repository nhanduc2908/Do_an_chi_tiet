<?php

namespace App\Services\Evaluation;

use App\Models\Evaluation;

class ScoringService
{
    public function calculateScore(Evaluation $evaluation): array
    {
        $totalScore = 0;
        $totalWeight = 0;

        foreach ($evaluation->details as $detail) {
            $criteria = $detail->criteria;
            $weight = $criteria->weight;
            $totalWeight += $weight;
            $totalScore += $detail->score;
        }

        $percentage = $totalWeight > 0 ? ($totalScore / $totalWeight) * 100 : 0;
        
        $evaluation->update([
            'total_score' => $totalScore,
            'max_score' => $totalWeight,
            'percentage' => $percentage,
        ]);

        return [
            'total_score' => $totalScore,
            'max_score' => $totalWeight,
            'percentage' => $percentage,
        ];
    }
}