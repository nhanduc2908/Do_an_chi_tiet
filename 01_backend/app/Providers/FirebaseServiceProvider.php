<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Firestore;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\DynamicLinks;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Factory::class, function ($app) {
            return (new Factory)
                ->withServiceAccount(config('firebase.credentials'))
                ->withDatabaseUri(config('firebase.database_url'));
        });

        $this->app->singleton(Auth::class, function ($app) {
            return $app->make(Factory::class)->createAuth();
        });

        $this->app->singleton(Firestore::class, function ($app) {
            return $app->make(Factory::class)->createFirestore();
        });

        $this->app->singleton(Messaging::class, function ($app) {
            return $app->make(Factory::class)->createMessaging();
        });

        $this->app->singleton(DynamicLinks::class, function ($app) {
            return $app->make(Factory::class)->createDynamicLinksService();
        });
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/firebase.php', 'firebase'
        );
    }
}