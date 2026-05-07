<?php

return [
    'backup' => [
        'name' => 'security-system',
        'source' => [
            'files' => [
                'include' => [
                    '01_backend',
                ],
                'exclude' => [
                    'storage/logs/*',
                    'storage/framework/*',
                ],
            ],
            'database' => [
                'mysql',
            ],
        ],
        'destination' => [
            'disks' => [
                'local',
                's3',
            ],
        ],
    ],
    'cleanup' => [
        'strategy' => 'keep_latest',
        'keep_latest' => 30,
        'keep_daily' => 7,
        'keep_weekly' => 4,
        'keep_monthly' => 12,
    ],
    'notifications' => [
        'enabled' => true,
        'channels' => ['mail'],
        'mail' => [
            'to' => env('BACKUP_MAIL_TO'),
        ],
        'slack' => [
            'webhook_url' => env('BACKUP_SLACK_WEBHOOK'),
        ],
    ],
];