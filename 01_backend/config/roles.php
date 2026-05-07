<?php

return [
    'default_roles' => [
        ['name' => 'admin', 'display_name' => 'Quản trị viên', 'level' => 100, 'color' => '#FF0000', 'icon' => 'shield-crown', 'requires_key' => true, 'requires_otp' => true],
        ['name' => 'dev', 'display_name' => 'Lập trình viên', 'level' => 30, 'color' => '#00FF00', 'icon' => 'code-braces', 'requires_key' => false, 'requires_otp' => false],
        ['name' => 'ba', 'display_name' => 'Phân tích nghiệp vụ', 'level' => 35, 'color' => '#00CCFF', 'icon' => 'chart-line', 'requires_key' => false, 'requires_otp' => false],
        ['name' => 'da', 'display_name' => 'Chuyên gia dữ liệu', 'level' => 35, 'color' => '#9900FF', 'icon' => 'database', 'requires_key' => false, 'requires_otp' => false],
        ['name' => 'hr', 'display_name' => 'Nhân sự', 'level' => 20, 'color' => '#FF9900', 'icon' => 'users', 'requires_key' => false, 'requires_otp' => false],
        ['name' => 'qa', 'display_name' => 'Kiểm thử', 'level' => 40, 'color' => '#FF00FF', 'icon' => 'bug', 'requires_key' => false, 'requires_otp' => false],
        ['name' => 'secops', 'display_name' => 'Vận hành bảo mật', 'level' => 50, 'color' => '#00FFFF', 'icon' => 'shield', 'requires_key' => false, 'requires_otp' => false],
        ['name' => 'auditor', 'display_name' => 'Kiểm toán', 'level' => 60, 'color' => '#FFFF00', 'icon' => 'clipboard', 'requires_key' => false, 'requires_otp' => false],
        ['name' => 'manager', 'display_name' => 'Quản lý', 'level' => 70, 'color' => '#FF6600', 'icon' => 'briefcase', 'requires_key' => false, 'requires_otp' => true],
        ['name' => 'ciso', 'display_name' => 'Giám đốc ATTT', 'level' => 90, 'color' => '#FF0066', 'icon' => 'crown', 'requires_key' => true, 'requires_otp' => true],
    ],
    'permissions' => [
        'admin' => ['*'],
        'dev' => ['evaluation.view', 'evaluation.create', 'scan.code.view', 'scan.code.run', 'vulnerability.view'],
        'ba' => ['evaluation.view', 'evaluation.create', 'process.view', 'risk.view'],
        'da' => ['evaluation.view', 'database.scan', 'data.analyze'],
        'hr' => ['evaluation.view', 'policy.view', 'training.view'],
        'qa' => ['evaluation.view', 'pentest.view', 'test.view'],
        'secops' => ['evaluation.view', 'monitoring.view', 'incident.view'],
        'auditor' => ['evaluation.view', 'audit.log.view', 'compliance.view'],
        'manager' => ['evaluation.view', 'evaluation.approve', 'team.view', 'report.view'],
        'ciso' => ['evaluation.view', 'strategy.view', 'budget.view', 'board.report'],
    ],
];