<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserActivity;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->user()) {
            UserActivity::updateOrCreate(
                ['user_id' => $request->user()->id, 'date' => now()->toDateString()],
                ['last_activity' => now(), 'activity_count' => \DB::raw('activity_count + 1')]
            );
        }

        return $response;
    }
}