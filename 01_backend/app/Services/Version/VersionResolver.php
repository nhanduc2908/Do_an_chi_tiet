<?php

namespace App\Services\Version;

use Illuminate\Http\Request;

class VersionResolver
{
    public function resolve(Request $request): string
    {
        $version = $request->header('X-Version') 
            ?? $request->query('version') 
            ?? session('version', 'v1');
        
        return in_array($version, ['v1','v2','v3','v4','v5']) ? $version : 'v1';
    }
}