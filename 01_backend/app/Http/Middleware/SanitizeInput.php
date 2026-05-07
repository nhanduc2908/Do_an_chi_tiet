<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $input = $request->all();
            array_walk_recursive($input, function (&$value) {
                $value = strip_tags(trim($value));
            });
            $request->merge($input);
        }

        return $next($request);
    }
}