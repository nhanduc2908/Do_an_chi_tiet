<?php

return [
    'domain' => env('HORIZON_DOMAIN'),
    'path' => 'horizon',
    'use' => 'default',
    'environments' => [
        'production' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default', 'ai_scoring', 'sync', 'notifications', 'reports'],
                'balance' => 'auto',
                'processes' => 10,
                'tries' => 3,
                'nice' => 0,
            ],
        ],
        'local' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 1,
                'nice' => 0,
            ],
        ],
    ],
    'defaults' => [
        'connection' => 'redis',
        'queue' => ['default'],
        'balance' => 'auto',
        'processes' => 1,
        'tries' => 1,
        'nice' => 0,
    ],
];