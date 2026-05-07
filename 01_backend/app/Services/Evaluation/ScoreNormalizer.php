<?php

namespace App\Services\Evaluation;

class ScoreNormalizer
{
    public function normalize(float $score, float $min = 0, float $max = 100): float
    {
        return ($score - $min) / ($max - $min) * 100;
    }

    public function zScore(float $score, float $mean, float $stdDev): float
    {
        return $stdDev > 0 ? ($score - $mean) / $stdDev : 0;
    }
}