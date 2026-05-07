<?php

namespace App\Services\Evaluation;

class WeightCalculator
{
    public function normalizeWeights(array $weights): array
    {
        $total = array_sum($weights);
        return $total > 0 ? array_map(fn($w) => $w / $total, $weights) : $weights;
    }

    public function calculateWeightedScore(array $scores, array $weights): float
    {
        $total = 0;
        foreach ($scores as $key => $score) {
            $total += $score * ($weights[$key] ?? 1);
        }
        return $total;
    }
}