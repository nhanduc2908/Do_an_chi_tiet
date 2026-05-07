<?php

return [
    'domain' => env('TELESCOPE_DOMAIN'),
    'path' => env('TELESCOPE_PATH', 'telescope'),
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],
    'middleware' => [
        'web',
        'auth',
        'role:admin',
    ],
    'only_paths' => [
        'api/*',
        'horizon/*',
        'telescope/*',
    ],
    'ignore_paths' => [
        'health',
        'metrics',
        'ping',
    ],
];