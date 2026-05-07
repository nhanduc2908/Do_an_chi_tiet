<?php

return [
    'channels' => [
        'database' => ['enabled' => true, 'retention_days' => 30],
        'mail' => ['enabled' => true, 'queue' => 'notifications'],
        'sms' => ['enabled' => false, 'queue' => 'notifications'],
        'push' => ['enabled' => true, 'queue' => 'notifications'],
        'webhook' => ['enabled' => true, 'queue' => 'notifications'],
        'slack' => ['enabled' => true, 'queue' => 'notifications'],
        'telegram' => ['enabled' => false, 'queue' => 'notifications'],
    ],
    'priorities' => [
        'low' => ['level' => 1, 'color' => '#888888'],
        'medium' => ['level' => 2, 'color' => '#FFCC00'],
        'high' => ['level' => 3, 'color' => '#FF6600'],
        'critical' => ['level' => 4, 'color' => '#FF0000'],
    ],
    'batch_size' => 100,
    'rate_limit_per_minute' => 60,
    'retry_times' => 3,
    'retry_delay' => 60,
];