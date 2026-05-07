<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AIEvaluationController;

/*
|--------------------------------------------------------------------------
| AI API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('ai')->middleware(['auth:sanctum'])->group(function () {
    
    // Scoring
    Route::post('/score/{evaluationId}', [AIEvaluationController::class, 'autoScore']);
    Route::get('/predict/{evaluationId}', [AIEvaluationController::class, 'predict']);
    
    // Analysis
    Route::get('/anomalies/{evaluationId}', [AIEvaluationController::class, 'detectAnomalies']);
    Route::get('/recommendations/{evaluationId}', [AIEvaluationController::class, 'getRecommendations']);
    Route::get('/insights/{evaluationId}', [AIEvaluationController::class, 'getInsights']);
    
    // Trends
    Route::get('/trend/{companyId}', [AIEvaluationController::class, 'predictTrend']);
    Route::get('/threat-prediction/{companyId}', [AIEvaluationController::class, 'predictThreat']);
    Route::post('/breach-probability', [AIEvaluationController::class, 'breachProbability']);
    
    // Models
    Route::get('/models', [AIEvaluationController::class, 'getModels']);
    Route::post('/models/train', [AIEvaluationController::class, 'trainModel']);
    Route::get('/models/{modelId}/evaluate', [AIEvaluationController::class, 'evaluateModel']);
});