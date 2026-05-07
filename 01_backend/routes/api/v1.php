<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Version 1 Routes
|--------------------------------------------------------------------------
| Basic features - SME version
*/

Route::prefix('v1')->middleware(['auth:sanctum', 'version:v1'])->group(function () {
    
    // Basic evaluation
    Route::apiResource('evaluations', EvaluationController::class)->only([
        'index', 'show', 'store', 'update'
    ]);
    Route::post('/evaluations/{id}/submit', [EvaluationController::class, 'submit']);
    
    // Basic report
    Route::get('/reports/{id}/download', [ReportController::class, 'download']);
    
    // User profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});