<?php

namespace App\Services\Evaluation;

use App\Models\Evaluation;
use App\Services\AI\AIScoringEngine;

class HybridScoringService
{
    protected AIScoringEngine $aiEngine;
    protected ScoringService $ruleEngine;

    public function __construct(AIScoringEngine $aiEngine, ScoringService $ruleEngine)
    {
        $this->aiEngine = $aiEngine;
        $this->ruleEngine = $ruleEngine;
    }

    public function calculateScore(Evaluation $evaluation, bool $useAI = true): array
    {
        if ($useAI && $this->isAIAvailable()) {
            try {
                return $this->aiEngine->autoScore($evaluation);
            } catch (\Exception $e) {
                return $this->ruleEngine->calculateScore($evaluation);
            }
        }
        return $this->ruleEngine->calculateScore($evaluation);
    }

    protected function isAIAvailable(): bool
    {
        return true;
    }
}