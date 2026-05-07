<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();

        // Admin có toàn quyền
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
            return null;
        });

        // Evaluation gates
        Gate::define('view-evaluation', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'auditor', 'ciso']);
        });

        Gate::define('create-evaluation', function (User $user) {
            return in_array($user->role, ['admin', 'dev', 'ba', 'da', 'qa']);
        });

        Gate::define('edit-evaluation', function (User $user) {
            return in_array($user->role, ['admin', 'dev', 'ba', 'da', 'qa']);
        });

        Gate::define('delete-evaluation', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('approve-evaluation', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'ciso']);
        });

        // Report gates
        Gate::define('view-report', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'ciso', 'auditor']);
        });

        Gate::define('generate-report', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'ciso', 'dev', 'qa']);
        });

        // User management gates
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-roles', function (User $user) {
            return $user->role === 'admin';
        });

        // Scan gates
        Gate::define('run-scan', function (User $user) {
            return in_array($user->role, ['admin', 'dev', 'qa', 'secops']);
        });

        // Audit gates
        Gate::define('view-audit', function (User $user) {
            return in_array($user->role, ['admin', 'auditor']);
        });

        // Training gates
        Gate::define('manage-training', function (User $user) {
            return in_array($user->role, ['admin', 'manager', 'hr']);
        });
    }
}
