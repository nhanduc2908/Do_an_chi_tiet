<?php

return [
    'defaults' => ['guard' => 'web', 'passwords' => 'users'],
    'guards' => [
        'web' => ['driver' => 'session', 'provider' => 'users'],
        'api' => ['driver' => 'sanctum', 'provider' => 'users'],
    ],
    'providers' => [
        'users' => ['driver' => 'eloquent', 'model' => App\Models\User::class],
    ],
    'passwords' => [
        'users' => ['provider' => 'users', 'table' => 'password_resets', 'expire' => 60, 'throttle' => 60],
    ],
    'password_timeout' => 10800,
    'jwt_secret' => env('JWT_SECRET'),
    'jwt_ttl' => env('JWT_TTL', 3600),
    'otp_expiry' => 5,
    'max_login_attempts' => 5,
    'lockout_time' => 15,
];