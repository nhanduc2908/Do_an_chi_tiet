<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\Auth\ResetPasswordController;
use App\Http\Controllers\Web\Auth\VerificationController;
use App\Http\Controllers\Web\Auth\TwoFactorController;

/*
|--------------------------------------------------------------------------
| Authentication Web Routes
|--------------------------------------------------------------------------
*/

// Login Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Forgot Password Routes
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->name('verification.verify')
        ->middleware(['signed']);
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

// Two-Factor Authentication Routes
Route::middleware(['auth'])->prefix('2fa')->name('2fa.')->group(function () {
    Route::get('/', [TwoFactorController::class, 'show'])->name('show');
    Route::post('/verify', [TwoFactorController::class, 'verify'])->name('verify');
    Route::post('/resend', [TwoFactorController::class, 'resend'])->name('resend');
    Route::get('/setup', [TwoFactorController::class, 'setup'])->name('setup');
    Route::post('/setup', [TwoFactorController::class, 'enable'])->name('enable');
    Route::post('/disable', [TwoFactorController::class, 'disable'])->name('disable');
    Route::get('/recovery-codes', [TwoFactorController::class, 'recoveryCodes'])->name('recovery-codes');
});

// OTP Routes (for low-level roles)
Route::middleware(['auth', 'require.otp'])->prefix('otp')->name('otp.')->group(function () {
    Route::get('/verify', [TwoFactorController::class, 'showOtpForm'])->name('verify');
    Route::post('/verify', [TwoFactorController::class, 'verifyOtp'])->name('verify.post');
});

// Security Key Routes (for high-level roles)
Route::middleware(['auth', 'require.key'])->prefix('key')->name('key.')->group(function () {
    Route::get('/verify', [TwoFactorController::class, 'showKeyForm'])->name('verify');
    Route::post('/verify', [TwoFactorController::class, 'verifyKey'])->name('verify.post');
    Route::get('/register', [TwoFactorController::class, 'registerKey'])->name('register');
    Route::post('/register', [TwoFactorController::class, 'storeKey'])->name('register.post');
});