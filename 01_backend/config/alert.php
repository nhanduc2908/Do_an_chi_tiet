<?php

return [
    'default_channels' => ['mail', 'push', 'database'],
    'throttle' => [
        'per_minute' => 10,
        'per_hour' => 100,
        'per_day' => 500,
    ],
    'severity_levels' => [
        'info' => ['send_email' => false, 'send_push' => false],
        'warning' => ['send_email' => true, 'send_push' => false],
        'error' => ['send_email' => true, 'send_push' => true],
        'critical' => ['send_email' => true, 'send_push' => true, 'send_sms' => true],
    ],
    'silence_period' => 300,
    'aggregation_window' => 60,
];