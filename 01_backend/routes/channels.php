<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Private user channel
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private company channel
Broadcast::channel('company.{id}', function ($user, $id) {
    return $user->company_id === (int) $id || $user->role === 'admin';
});

// Private device channel
Broadcast::channel('device.{deviceId}', function ($user, $deviceId) {
    return $user->devices()->where('device_id', $deviceId)->exists();
});

// Private session channel
Broadcast::channel('session.{sessionId}', function ($user, $sessionId) {
    return $user->sessions()->where('session_id', $sessionId)->exists();
});

// Public channels
Broadcast::channel('login-events', function ($user) {
    return true;
});

Broadcast::channel('device-events', function ($user) {
    return true;
});

Broadcast::channel('security-alerts', function ($user) {
    return in_array($user->role, ['admin', 'secops', 'ciso']);
});