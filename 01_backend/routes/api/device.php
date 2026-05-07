<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceLinkController;
use App\Http\Controllers\Api\ScreenController;

/*
|--------------------------------------------------------------------------
| Device API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('device')->middleware(['auth:sanctum'])->group(function () {
    
    // Device management
    Route::get('/', [DeviceController::class, 'index']);
    Route::post('/register', [DeviceController::class, 'register']);
    Route::put('/{deviceId}', [DeviceController::class, 'update']);
    Route::delete('/{deviceId}', [DeviceController::class, 'unregister']);
    Route::post('/{deviceId}/heartbeat', [DeviceController::class, 'heartbeat']);
    
    // Device linking
    Route::post('/link/generate', [DeviceLinkController::class, 'generateCode']);
    Route::post('/link', [DeviceLinkController::class, 'linkDevice']);
    Route::delete('/link/{deviceId}', [DeviceLinkController::class, 'unlinkDevice']);
    Route::get('/linked', [DeviceLinkController::class, 'linkedDevices']);
    
    // Screen
    Route::post('/screen/log', [DeviceController::class, 'screenLog']);
    Route::post('/screen/detect', [ScreenController::class, 'detect']);
    Route::get('/screen/layout', [ScreenController::class, 'getLayout']);
    Route::get('/screen/statistics', [ScreenController::class, 'statistics']);
});