<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StatisticsController;

/*
|--------------------------------------------------------------------------
| API Version 2 Routes
|--------------------------------------------------------------------------
| Advanced features - Mid-market version
*/

Route::prefix('v2')->middleware(['auth:sanctum', 'version:v2'])->group(function () {
    
    // Advanced evaluation
    Route::apiResource('evaluations', EvaluationController::class);
    Route::post('/evaluations/{id}/approve', [EvaluationController::class, 'approve']);
    Route::post('/evaluations/{id}/reject', [EvaluationController::class, 'reject']);
    Route::get('/evaluations/statistics', [StatisticsController::class, 'overview']);
    
    // Advanced report
    Route::post('/reports/generate', [ReportController::class, 'generate']);
    Route::get('/reports', [ReportController::class, 'index']);
    
    // Team evaluation
    Route::get('/team/evaluations', [EvaluationController::class, 'teamEvaluations']);
});