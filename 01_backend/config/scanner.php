<?php

return [
    'scan_types' => [
        'code' => ['name' => 'Quét mã nguồn', 'enabled' => true, 'timeout' => 600],
        'web' => ['name' => 'Quét Web', 'enabled' => true, 'timeout' => 900],
        'database' => ['name' => 'Quét Database', 'enabled' => true, 'timeout' => 300],
        'password' => ['name' => 'Quét mật khẩu', 'enabled' => true, 'timeout' => 180],
        'network' => ['name' => 'Quét mạng', 'enabled' => true, 'timeout' => 1200],
        'api' => ['name' => 'Quét API', 'enabled' => true, 'timeout' => 600],
        'mobile' => ['name' => 'Quét Mobile', 'enabled' => true, 'timeout' => 900],
        'container' => ['name' => 'Quét Container', 'enabled' => true, 'timeout' => 600],
        'cloud' => ['name' => 'Quét Cloud', 'enabled' => true, 'timeout' => 1200],
    ],
    'max_concurrent_scans' => 5,
    'max_scan_history' => 100,
    'results_retention_days' => 30,
    'vulnerability_severity' => [
        'critical' => ['color' => '#FF0000', 'score' => 9.0],
        'high' => ['color' => '#FF6600', 'score' => 7.0],
        'medium' => ['color' => '#FFCC00', 'score' => 4.0],
        'low' => ['color' => '#00CC00', 'score' => 0.1],
        'info' => ['color' => '#888888', 'score' => 0],
    ],
];