<?php

return [
    'collectors' => [
        'request' => ['enabled' => true, 'interval' => 60],
        'database' => ['enabled' => true, 'interval' => 300],
        'cache' => ['enabled' => true, 'interval' => 60],
        'queue' => ['enabled' => true, 'interval' => 60],
        'ai' => ['enabled' => true, 'interval' => 300],
        'user' => ['enabled' => true, 'interval' => 300],
        'evaluation' => ['enabled' => true, 'interval' => 300],
    ],
    'export_interval' => 60,
    'export_driver' => 'prometheus',
    'prometheus_namespace' => 'security_system',
    'retention_points' => 10080,
];