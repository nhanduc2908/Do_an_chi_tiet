<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\BackupController;
use App\Http\Controllers\Web\AuditController;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
| All routes require admin role
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [AdminController::class, 'stats'])->name('dashboard.stats');
    
    // User management
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::get('/users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::post('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions');
    
    // Role management
    Route::resource('roles', RoleController::class);
    Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::post('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
    Route::post('/roles/initialize', [RoleController::class, 'initializeDefault'])->name('roles.initialize');
    
    // Company management
    Route::resource('companies', CompanyController::class);
    Route::get('/companies/{company}/departments', [CompanyController::class, 'departments'])->name('companies.departments');
    Route::get('/companies/{company}/users', [CompanyController::class, 'users'])->name('companies.users');
    Route::post('/companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/security', [SettingController::class, 'security'])->name('settings.security');
    Route::post('/settings/security', [SettingController::class, 'updateSecurity'])->name('settings.update-security');
    Route::get('/settings/mail', [SettingController::class, 'mail'])->name('settings.mail');
    Route::post('/settings/mail/test', [SettingController::class, 'testMail'])->name('settings.test-mail');
    Route::get('/settings/integration', [SettingController::class, 'integration'])->name('settings.integration');
    Route::post('/settings/integration', [SettingController::class, 'updateIntegration'])->name('settings.update-integration');
    
    // Backup
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
    Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    
    // Audit
    Route::get('/audit/logs', [AuditController::class, 'logs'])->name('audit.logs');
    Route::get('/audit/login-history', [AuditController::class, 'loginHistory'])->name('audit.login-history');
    Route::get('/audit/data-changes', [AuditController::class, 'dataChanges'])->name('audit.data-changes');
    Route::get('/audit/security-violations', [AuditController::class, 'securityViolations'])->name('audit.security-violations');
    Route::get('/audit/export', [AuditController::class, 'export'])->name('audit.export');
    
    // System info
    Route::get('/system/info', [AdminController::class, 'systemInfo'])->name('system.info');
    Route::get('/system/health', [AdminController::class, 'health'])->name('system.health');
    Route::post('/system/clear-cache', [AdminController::class, 'clearCache'])->name('system.clear-cache');
    Route::post('/system/optimize', [AdminController::class, 'optimize'])->name('system.optimize');
});