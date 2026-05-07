<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/documentation', [HomeController::class, 'documentation'])->name('documentation');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::post('/contact/send', [HomeController::class, 'sendContact'])->name('contact.send');

// Auth routes (Laravel UI)
Auth::routes();

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');
});

// Role-based dashboards (will be handled by DashboardController)
Route::middleware(['auth'])->prefix('role')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('role.admin');
    Route::get('/manager', [DashboardController::class, 'index'])->name('role.manager');
    Route::get('/ciso', [DashboardController::class, 'index'])->name('role.ciso');
    Route::get('/auditor', [DashboardController::class, 'index'])->name('role.auditor');
});