<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // User repository
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );

        // Evaluation repository
        $this->app->bind(
            \App\Repositories\Contracts\EvaluationRepositoryInterface::class,
            \App\Repositories\Eloquent\EvaluationRepository::class
        );

        // Report repository
        $this->app->bind(
            \App\Repositories\Contracts\ReportRepositoryInterface::class,
            \App\Repositories\Eloquent\ReportRepository::class
        );

        // Audit repository
        $this->app->bind(
            \App\Repositories\Contracts\AuditRepositoryInterface::class,
            \App\Repositories\Eloquent\AuditRepository::class
        );

        // Criteria repository
        $this->app->bind(
            \App\Repositories\Contracts\CriteriaRepositoryInterface::class,
            \App\Repositories\Eloquent\CriteriaRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}