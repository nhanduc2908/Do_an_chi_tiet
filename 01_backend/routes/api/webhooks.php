<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
*/

Route::prefix('webhooks')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    
    // Webhook management
    Route::get('/', [WebhookController::class, 'index']);
    Route::post('/', [WebhookController::class, 'store']);
    Route::get('/{webhook}', [WebhookController::class, 'show']);
    Route::put('/{webhook}', [WebhookController::class, 'update']);
    Route::delete('/{webhook}', [WebhookController::class, 'destroy']);
    
    // Webhook deliveries
    Route::get('/{webhook}/deliveries', [WebhookController::class, 'deliveries']);
    Route::post('/{webhook}/deliveries/{deliveryId}/retry', [WebhookController::class, 'retry']);
    
    // Test webhook
    Route::post('/{webhook}/test', [WebhookController::class, 'test']);
});

// Public webhook endpoints (no auth)
Route::post('/webhooks/receive/{token}', [WebhookController::class, 'receive'])->name('webhook.receive');