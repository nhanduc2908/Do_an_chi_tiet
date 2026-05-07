<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\ShareController;

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
| No authentication required
*/

// Home and landing pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/features', [PageController::class, 'features'])->name('features');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/cookies', [PageController::class, 'cookies'])->name('cookies');

// Documentation
Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', [PageController::class, 'documentation'])->name('index');
    Route::get('/getting-started', [PageController::class, 'gettingStarted'])->name('getting-started');
    Route::get('/installation', [PageController::class, 'installation'])->name('installation');
    Route::get('/configuration', [PageController::class, 'configuration'])->name('configuration');
    Route::get('/api-reference', [PageController::class, 'apiReference'])->name('api-reference');
    Route::get('/webhooks', [PageController::class, 'webhooks'])->name('webhooks');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{category}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
    Route::post('/{slug}/comment', [BlogController::class, 'comment'])->name('comment')->middleware('throttle:5,1');
});

// Share (public access)
Route::get('/share/{token}', [ShareController::class, 'access'])->name('share.access');
Route::get('/share/{token}/download', [ShareController::class, 'download'])->name('share.download');

// Status page
Route::get('/status', [PageController::class, 'status'])->name('status');
Route::get('/health', [PageController::class, 'health'])->name('health');

// Sitemap
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PageController::class, 'robots'])->name('robots');

// Language switcher
Route::get('/lang/{locale}', [PageController::class, 'switchLanguage'])->name('lang.switch');

// Version switcher (public demo)
Route::get('/demo/{version}', [PageController::class, 'demoVersion'])->name('demo.version');

// Newsletter
Route::post('/newsletter/subscribe', [PageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::post('/newsletter/unsubscribe', [PageController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');

// Webhook receivers (public)
Route::post('/webhook/stripe', [WebhookController::class, 'stripe'])->name('webhook.stripe');
Route::post('/webhook/sendgrid', [WebhookController::class, 'sendgrid'])->name('webhook.sendgrid');