<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ComplianceController;
use App\Http\Controllers\Api\RiskController;
use App\Http\Controllers\Api\AIEvaluationController;

/*
|--------------------------------------------------------------------------
| API Version 4 Routes
|--------------------------------------------------------------------------
| Compliance + Risk - Finance version
*/

Route::prefix('v4')->middleware(['auth:sanctum', 'version:v4'])->group(function () {
    
    // Compliance
    Route::get('/compliance/check', [ComplianceController::class, 'check']);
    Route::get('/compliance/report', [ComplianceController::class, 'report']);
    Route::get('/compliance/history', [ComplianceController::class, 'history']);
    
    // Risk management
    Route::apiResource('risks', RiskController::class);
    Route::get('/risk/matrix', [RiskController::class, 'matrix']);
    Route::get('/risk/heatmap', [RiskController::class, 'heatmap']);
    
    // Advanced AI
    Route::get('/ai/trend/{companyId}', [AIEvaluationController::class, 'predictTrend']);
    Route::get('/ai/insights/{evaluationId}', [AIEvaluationController::class, 'getInsights']);
    
    // Full evaluation with compliance
    Route::apiResource('evaluations', EvaluationController::class);
    Route::post('/evaluations/{id}/compliance', [EvaluationController::class, 'checkCompliance']);
});