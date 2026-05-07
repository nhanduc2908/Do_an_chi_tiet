<?php

return [
    'fhhe' => ['master_key' => env('FHHE_MASTER_KEY'), 'algorithm' => 'AES-256-GCM', 'key_rotation_days' => 30],
    'csp' => ['enabled' => true, 'report_only' => env('CSP_REPORT_ONLY', false)],
    'headers' => [
        'X-Frame-Options' => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
    ],
    'rate_limit' => ['enabled' => true, 'max_attempts' => 60, 'decay_minutes' => 1],
    'session' => ['lifetime' => 120, 'expire_on_close' => false, 'secure' => env('SESSION_SECURE_COOKIE', false)],
];