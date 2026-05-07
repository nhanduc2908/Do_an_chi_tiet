<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAgentValidator
{
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->userAgent();
        
        $allowedAgents = config('security.allowed_user_agents', []);
        
        if (!empty($allowedAgents)) {
            $isAllowed = false;
            foreach ($allowedAgents as $pattern) {
                if (preg_match($pattern, $userAgent)) {
                    $isAllowed = true;
                    break;
                }
            }
            
            if (!$isAllowed) {
                return response()->json(['message' => 'Invalid user agent'], 403);
            }
        }

        return $next($request);
    }
}