<?php

return [
    'version' => env('API_VERSION', 'v1'),
    'versions' => ['v1', 'v2', 'v3', 'v4', 'v5'],
    'default_version' => 'v1',
    'rate_limit' => [
        'enabled' => true,
        'per_minute' => env('API_RATE_LIMIT_PER_MINUTE', 60),
        'per_hour' => env('API_RATE_LIMIT_PER_HOUR', 1000),
        'per_day' => env('API_RATE_LIMIT_PER_DAY', 10000),
    ],
    'response_format' => 'json',
    'debug' => env('API_DEBUG', false),
    'include_timestamp' => true,
    'include_request_id' => true,
    'pagination' => [
        'default_per_page' => 15,
        'max_per_page' => 100,
    ],
    'throttle' => [
        'enabled' => true,
        'redis_key_prefix' => 'api_throttle_',
    ],
];