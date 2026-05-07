<?php

return [
    'enabled' => true,
    'mode' => 'prevention', // prevention, detection, disabled
    'rules' => [
        'sql_injection' => ['enabled' => true, 'action' => 'block'],
        'xss' => ['enabled' => true, 'action' => 'block'],
        'rce' => ['enabled' => true, 'action' => 'block'],
        'lfi' => ['enabled' => true, 'action' => 'block'],
        'path_traversal' => ['enabled' => true, 'action' => 'block'],
        'user_agent' => ['enabled' => true, 'action' => 'log'],
    ],
    'custom_rules' => [],
    'exclude_urls' => ['/health', '/metrics', '/webhook'],
    'log_blocked_requests' => true,
];