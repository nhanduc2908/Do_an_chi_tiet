<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VersionMiddleware
{
    protected array $allowedVersions = ['v1', 'v2', 'v3', 'v4', 'v5'];

    public function handle(Request $request, Closure $next)
    {
        $version = $request->header('X-Version') 
            ?? $request->query('version') 
            ?? session('version') 
            ?? config('app.default_version', 'v1');

        if (!in_array($version, $this->allowedVersions)) {
            $version = 'v1';
        }

        $request->attributes->set('version', $version);
        session(['version' => $version]);

        return $next($request);
    }
}