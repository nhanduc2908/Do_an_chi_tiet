<?php

namespace App\Services\Evaluation;

use App\Models\Evaluation;

class ComplianceChecker
{
    public function checkCompliance(Evaluation $evaluation, string $standard): array
    {
        $score = $evaluation->percentage;
        $isCompliant = $score >= ($standard === 'iso27001' ? 80 : 75);
        
        return [
            'standard' => $standard,
            'score' => $score,
            'is_compliant' => $isCompliant,
        ];
    }
}