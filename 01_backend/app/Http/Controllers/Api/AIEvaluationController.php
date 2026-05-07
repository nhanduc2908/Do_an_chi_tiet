<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Services\AI\AIScoringEngine;
use App\Services\AI\PredictiveAnalytics;
use App\Services\AI\AnomalyDetector;
use App\Services\AI\RecommendationAI;
use Illuminate\Http\Request;

class AIEvaluationController extends Controller
{
    protected AIScoringEngine $aiScoring;
    protected PredictiveAnalytics $predictive;
    protected AnomalyDetector $anomaly;
    protected RecommendationAI $recommendation;

    public function __construct(
        AIScoringEngine $aiScoring,
        PredictiveAnalytics $predictive,
        AnomalyDetector $anomaly,
        RecommendationAI $recommendation
    ) {
        $this->aiScoring = $aiScoring;
        $this->predictive = $predictive;
        $this->anomaly = $anomaly;
        $this->recommendation = $recommendation;
    }

    public function autoScore($evaluationId)
    {
        $evaluation = Evaluation::findOrFail($evaluationId);
        $result = $this->aiScoring->autoScore($evaluation);
        return response()->json($result);
    }

    public function predict($evaluationId, Request $request)
    {
        $days = $request->get('days', 90);
        $evaluation = Evaluation::findOrFail($evaluationId);
        $result = $this->aiScoring->predictScore($evaluation, $days);
        return response()->json($result);
    }

    public function detectAnomalies($evaluationId)
    {
        $evaluation = Evaluation::findOrFail($evaluationId);
        $result = $this->anomaly->detectEvaluationAnomalies($evaluation);
        return response()->json($result);
    }

    public function getRecommendations($evaluationId)
    {
        $evaluation = Evaluation::findOrFail($evaluationId);
        $result = $this->recommendation->generateRecommendations($evaluation);
        return response()->json($result);
    }

    public function predictTrend($companyId)
    {
        $company = \App\Models\Company::findOrFail($companyId);
        $result = $this->predictive->predictSecurityTrend($company);
        return response()->json($result);
    }
}