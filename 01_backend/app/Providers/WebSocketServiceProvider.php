<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WebSocket\WebSocketManager;
use App\Services\WebSocket\DeviceConnectionManager;
use App\Services\WebSocket\MessageBroadcaster;
use App\Services\WebSocket\SessionSyncService;

class WebSocketServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WebSocketManager::class, function ($app) {
            return new WebSocketManager();
        });

        $this->app->singleton(DeviceConnectionManager::class, function ($app) {
            return new DeviceConnectionManager();
        });

        $this->app->singleton(MessageBroadcaster::class, function ($app) {
            return new MessageBroadcaster();
        });

        $this->app->singleton(SessionSyncService::class, function ($app) {
            return new SessionSyncService();
        });
    }

    public function boot(): void
    {
        // Start WebSocket server if in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\WebSocket\StartServer::class,
            ]);
        }
    }
}