<?php

return [
    'enabled' => true,
    'default_rules' => [
        'allow_local' => ['enabled' => true, 'ips' => ['127.0.0.1', '::1', 'localhost']],
        'block_suspicious' => ['enabled' => true, 'patterns' => ['/wp-admin', '/phpmyadmin', '/.env']],
        'rate_limit' => ['enabled' => true, 'max_requests' => 100, 'decay_minutes' => 1],
    ],
    'log_level' => 'info',
    'block_duration_minutes' => 60,
    'max_failures_before_block' => 10,
    'whitelist_enabled' => true,
    'blacklist_enabled' => true,
];