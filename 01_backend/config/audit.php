<?php

return [
    'log_events' => [
        'login', 'logout', 'create', 'update', 'delete', 
        'export', 'import', 'approve', 'reject', 'share'
    ],
    'retention_days' => 90,
    'exclude_ips' => ['127.0.0.1', '::1'],
    'exclude_users' => ['system'],
    'log_request_body' => false,
    'log_response_body' => false,
    'sensitive_fields' => ['password', 'token', 'secret', 'key', 'authorization'],
    'audit_trail_enabled' => true,
];