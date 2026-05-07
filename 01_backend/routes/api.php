<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuditController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\StatisticsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected routes
Route::middleware(['auth:sanctum', 'version'])->group(function () {
    
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // OTP & Key verification
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/verify-key', [AuthController::class, 'verifyKey']);
    
    // Evaluation routes
    Route::apiResource('evaluations', EvaluationController::class);
    Route::post('/evaluations/{id}/submit', [EvaluationController::class, 'submit']);
    Route::post('/evaluations/{id}/approve', [EvaluationController::class, 'approve']);
    Route::post('/evaluations/{id}/reject', [EvaluationController::class, 'reject']);
    Route::put('/evaluations/{evaluationId}/details/{detailId}', [EvaluationController::class, 'updateDetail']);
    Route::post('/evaluations/batch-save', [EvaluationController::class, 'batchSave']);
    Route::get('/evaluations/statistics', [EvaluationController::class, 'statistics']);
    Route::get('/evaluations/dashboard', [EvaluationController::class, 'dashboard']);
    
    // Report routes
    Route::apiResource('reports', ReportController::class);
    Route::post('/reports/generate', [ReportController::class, 'generate']);
    Route::post('/reports/schedule', [ReportController::class, 'schedule']);
    Route::get('/reports/{id}/download', [ReportController::class, 'download']);
    
    // Sync routes
    Route::post('/sync/pull', [SyncController::class, 'pull']);
    Route::post('/sync/push', [SyncController::class, 'push']);
    Route::post('/sync/resolve', [SyncController::class, 'resolveConflict']);
    Route::get('/sync/status', [SyncController::class, 'status']);
    
    // Statistics routes
    Route::get('/statistics/overview', [StatisticsController::class, 'overview']);
    Route::get('/statistics/trend', [StatisticsController::class, 'evaluationTrend']);
    Route::get('/statistics/activity', [StatisticsController::class, 'userActivity']);
    Route::get('/statistics/performance', [StatisticsController::class, 'domainPerformance']);
    
    // User management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
        Route::put('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
        Route::post('/roles/assign', [RoleController::class, 'assignRole']);
        Route::post('/roles/{id}/permissions', [RoleController::class, 'assignPermissions']);
    });
    
    // Audit routes (Admin & Auditor)
    Route::middleware(['role:admin,auditor'])->group(function () {
        Route::get('/audit/logs', [AuditController::class, 'index']);
        Route::get('/audit/login-history', [AuditController::class, 'loginHistory']);
        Route::get('/audit/data-changes', [AuditController::class, 'dataChanges']);
        Route::get('/audit/security-violations', [AuditController::class, 'securityViolations']);
    });
    
    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/admin/system-info', [AdminController::class, 'systemInfo']);
        Route::post('/admin/backup', [AdminController::class, 'backup']);
        Route::post('/admin/clear-cache', [AdminController::class, 'clearCache']);
    });
});