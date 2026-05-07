<?php

return [
    'classifications' => [
        'public' => ['name' => 'Công khai', 'level' => 1, 'color' => '#00CC00'],
        'internal' => ['name' => 'Nội bộ', 'level' => 2, 'color' => '#0099FF'],
        'confidential' => ['name' => 'Mật', 'level' => 3, 'color' => '#FFCC00'],
        'restricted' => ['name' => 'Hạn chế', 'level' => 4, 'color' => '#FF6600'],
        'top_secret' => ['name' => 'Tuyệt mật', 'level' => 5, 'color' => '#FF0000'],
    ],
    'types' => [
        'hardware' => 'Phần cứng',
        'software' => 'Phần mềm',
        'data' => 'Dữ liệu',
        'service' => 'Dịch vụ',
        'person' => 'Nhân sự',
        'facility' => 'Cơ sở vật chất',
        'cloud' => 'Đám mây',
        'network' => 'Mạng',
    ],
    'criticality_levels' => [
        'low' => 1,
        'medium' => 2,
        'high' => 3,
        'critical' => 4,
    ],
    'auto_classify_enabled' => true,
];