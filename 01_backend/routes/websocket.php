<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebSocketController;

/*
|--------------------------------------------------------------------------
| WebSocket Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/websocket/auth', [WebSocketController::class, 'auth']);
    Route::get('/websocket/connections', [WebSocketController::class, 'connections']);
    Route::delete('/websocket/disconnect/{connectionId}', [WebSocketController::class, 'disconnect']);
    Route::post('/websocket/broadcast', [WebSocketController::class, 'broadcast']);
    Route::get('/websocket/stats', [WebSocketController::class, 'stats']);
});