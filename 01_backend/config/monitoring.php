<?php

return [
    'metrics_retention_days' => 30,
    'alert_channels' => ['mail', 'slack', 'telegram', 'webhook'],
    'health_check_interval' => 60,
    'endpoints' => [
        'api' => ['url' => '/api/health', 'expected_status' => 200],
        'database' => ['check' => 'db', 'timeout' => 5],
        'redis' => ['check' => 'redis', 'timeout' => 5],
        'elasticsearch' => ['check' => 'elastic', 'timeout' => 10],
    ],
    'thresholds' => [
        'response_time_warning' => 1000,
        'response_time_critical' => 3000,
        'cpu_warning' => 70,
        'cpu_critical' => 90,
        'memory_warning' => 80,
        'memory_critical' => 95,
    ],
];