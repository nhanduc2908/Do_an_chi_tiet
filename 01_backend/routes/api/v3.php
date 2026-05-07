<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\AIEvaluationController;

/*
|--------------------------------------------------------------------------
| API Version 3 Routes
|--------------------------------------------------------------------------
| Real-time sync + AI - Enterprise version
*/

Route::prefix('v3')->middleware(['auth:sanctum', 'version:v3'])->group(function () {
    
    // Real-time sync
    Route::post('/sync/pull', [SyncController::class, 'pull']);
    Route::post('/sync/push', [SyncController::class, 'push']);
    Route::post('/sync/resolve', [SyncController::class, 'resolveConflict']);
    
    // AI features
    Route::post('/ai/score/{evaluationId}', [AIEvaluationController::class, 'autoScore']);
    Route::get('/ai/predict/{evaluationId}', [AIEvaluationController::class, 'predict']);
    Route::get('/ai/anomalies/{evaluationId}', [AIEvaluationController::class, 'detectAnomalies']);
    Route::get('/ai/recommendations/{evaluationId}', [AIEvaluationController::class, 'getRecommendations']);
    
    // Full evaluation
    Route::apiResource('evaluations', EvaluationController::class);
    Route::post('/evaluations/batch-save', [EvaluationController::class, 'batchSave']);
    
    // Scheduled reports
    Route::post('/reports/schedule', [ReportController::class, 'schedule']);
    Route::get('/reports/scheduled', [ReportController::class, 'scheduled']);
});