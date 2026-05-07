<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ThreatIntelController;
use App\Http\Controllers\Api\CrossDeviceController;
use App\Http\Controllers\Api\AIEvaluationController;

/*
|--------------------------------------------------------------------------
| API Version 5 Routes
|--------------------------------------------------------------------------
| Dark web + Threat intelligence - Government version
*/

Route::prefix('v5')->middleware(['auth:sanctum', 'version:v5', 'fhhe'])->group(function () {
    
    // Threat intelligence
    Route::get('/threat/intel', [ThreatIntelController::class, 'indicators']);
    Route::post('/threat/check', [ThreatIntelController::class, 'checkIndicator']);
    Route::get('/threat/dark-web', [ThreatIntelController::class, 'darkWeb']);
    Route::post('/threat/dark-web/keyword', [ThreatIntelController::class, 'addDarkWebKeyword']);
    Route::get('/threat/analyze/{indicator}', [ThreatIntelController::class, 'analyze']);
    Route::get('/threat/dashboard', [ThreatIntelController::class, 'dashboard']);
    
    // Cross-device
    Route::get('/device/sessions', [CrossDeviceController::class, 'sessions']);
    Route::post('/device/session', [CrossDeviceController::class, 'createSession']);
    Route::post('/device/message', [CrossDeviceController::class, 'sendMessage']);
    Route::get('/device/messages', [CrossDeviceController::class, 'getMessages']);
    Route::post('/device/message/{id}/read', [CrossDeviceController::class, 'markAsRead']);
    Route::delete('/device/session/{sessionId}', [CrossDeviceController::class, 'endSession']);
    
    // Advanced AI with threat intelligence
    Route::get('/ai/threat-prediction/{companyId}', [AIEvaluationController::class, 'predictThreat']);
    Route::post('/ai/breach-probability', [AIEvaluationController::class, 'breachProbability']);
    
    // Secure evaluation
    Route::apiResource('evaluations', EvaluationController::class)->middleware('fhhe.encrypt');
});