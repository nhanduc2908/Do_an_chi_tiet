<?php

return [
    'severity_levels' => [
        'critical' => ['level' => 4, 'color' => '#FF0000', 'response_time' => 15],
        'high' => ['level' => 3, 'color' => '#FF6600', 'response_time' => 60],
        'medium' => ['level' => 2, 'color' => '#FFCC00', 'response_time' => 240],
        'low' => ['level' => 1, 'color' => '#00CC00', 'response_time' => 720],
    ],
    'types' => [
        'breach' => 'Rò rỉ dữ liệu',
        'attack' => 'Tấn công mạng',
        'malware' => 'Malware',
        'misconfiguration' => 'Cấu hình sai',
        'insider' => 'Người dùng nội bộ',
        'other' => 'Khác',
    ],
    'statuses' => [
        'open' => 'Đang mở',
        'investigating' => 'Đang điều tra',
        'contained' => 'Đã khoanh vùng',
        'resolved' => 'Đã xử lý',
        'closed' => 'Đã đóng',
    ],
    'auto_escalation' => true,
    'escalation_timeout' => 30,
    'notification_channels' => ['email', 'slack', 'sms'],
];