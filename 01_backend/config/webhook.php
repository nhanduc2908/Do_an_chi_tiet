<?php

return [
    'retry_times' => 3,
    'retry_delay' => 5,
    'timeout' => 10,
    'user_agent' => 'SecuritySystem-Webhook/1.0',
    'max_payload_size' => 1024 * 1024, // 1MB
    'events' => [
        'evaluation.submitted' => 'Đánh giá được gửi',
        'evaluation.approved' => 'Đánh giá được duyệt',
        'report.generated' => 'Báo cáo được tạo',
        'security.alert' => 'Cảnh báo bảo mật',
        'sync.completed' => 'Đồng bộ hoàn tất',
    ],
    'verify_signature' => true,
    'signature_header' => 'X-Webhook-Signature',
];