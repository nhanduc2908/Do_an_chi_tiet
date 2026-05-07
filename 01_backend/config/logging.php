<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),
    'deprecations' => ['channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'), 'trace' => false],
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'audit', 'security'],
            'ignore_exceptions' => false,
        ],
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
            'replace_placeholders' => true,
        ],
        'audit' => [
            'driver' => 'daily',
            'path' => storage_path('logs/audit.log'),
            'level' => 'info',
            'days' => 30,
        ],
        'security' => [
            'driver' => 'daily',
            'path' => storage_path('logs/security.log'),
            'level' => 'warning',
            'days' => 90,
        ],
        'login' => [
            'driver' => 'daily',
            'path' => storage_path('logs/login.log'),
            'level' => 'info',
            'days' => 30,
        ],
        'sync' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sync.log'),
            'level' => 'info',
            'days' => 7,
        ],
        'ai' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ai.log'),
            'level' => 'debug',
            'days' => 14,
        ],
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],
        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],
        'null' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\NullHandler::class,
        ],
      'channels' => [
    'audit' => [
        'driver' => 'daily',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
        'days' => 90,
    ],
    'security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/security.log'),
        'level' => 'warning',
        'days' => 365,
    ],
    'login' => [
        'driver' => 'daily',
        'path' => storage_path('logs/login.log'),
        'level' => 'info',
        'days' => 180,
    ],
    'sync' => [
        'driver' => 'daily',
        'path' => storage_path('logs/sync.log'),
        'level' => 'info',
        'days' => 30,
    ],
]  
    ],
];