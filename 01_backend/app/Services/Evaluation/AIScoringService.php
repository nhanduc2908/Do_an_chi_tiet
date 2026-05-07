<?php

namespace App\Services\Evaluation;

use App\Models\Evaluation;
use App\Services\AI\AIScoringEngine;

class AIScoringService
{
    protected AIScoringEngine $aiEngine;

    public function __construct(AIScoringEngine $aiEngine)
    {
        $this->aiEngine = $aiEngine;
    }

    public function score(Evaluation $evaluation): array
    {
        return $this->aiEngine->autoScore($evaluation);
    }

    public function predict(Evaluation $evaluation, int $days = 90): array
    {
        return $this->aiEngine->predictScore($evaluation, $days);
    }
}