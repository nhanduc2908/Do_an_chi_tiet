<?php

return [
    'modules' => [
        'security_awareness' => [
            'name' => 'Nhận thức bảo mật',
            'duration' => 60,
        ],
        'data_protection' => [
            'name' => 'Bảo vệ dữ liệu',
            'duration' => 90,
        ],
        'incident_response' => [
            'name' => 'Phản ứng sự cố',
            'duration' => 120,
        ],
    ],
    'certificate_expiry' => 365,
    'reminder_days' => [30, 14, 7, 1],
];