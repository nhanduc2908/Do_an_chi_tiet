<?php

return [
    'cache_ttl' => 3600,
    'default_permissions' => [
        'view', 'create', 'edit', 'delete', 'approve', 
        'export', 'import', 'share', 'audit'
    ],
    'modules' => [
        'evaluation' => 'Đánh giá',
        'report' => 'Báo cáo',
        'user' => 'Người dùng',
        'role' => 'Vai trò',
        'company' => 'Công ty',
        'criteria' => 'Tiêu chí',
        'domain' => 'Lĩnh vực',
        'scan' => 'Quét',
        'training' => 'Đào tạo',
        'incident' => 'Sự cố',
        'risk' => 'Rủi ro',
        'asset' => 'Tài sản',
        'vendor' => 'Nhà cung cấp',
    ],
    'dynamic_permissions' => true,
];