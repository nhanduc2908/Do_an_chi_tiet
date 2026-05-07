<?php

return [
    'matrix_size' => 5,
    'likelihood_labels' => [
        1 => 'Rất thấp',
        2 => 'Thấp',
        3 => 'Trung bình',
        4 => 'Cao',
        5 => 'Rất cao',
    ],
    'impact_labels' => [
        1 => 'Rất thấp',
        2 => 'Thấp',
        3 => 'Trung bình',
        4 => 'Cao',
        5 => 'Rất cao',
    ],
    'risk_levels' => [
        'low' => ['min' => 1, 'max' => 4, 'color' => '#00CC00', 'action' => 'Chấp nhận'],
        'medium' => ['min' => 5, 'max' => 9, 'color' => '#FFCC00', 'action' => 'Theo dõi'],
        'high' => ['min' => 10, 'max' => 16, 'color' => '#FF6600', 'action' => 'Cần xử lý'],
        'critical' => ['min' => 17, 'max' => 25, 'color' => '#FF0000', 'action' => 'Xử lý ngay'],
    ],
    'default_risk_tolerance' => 6,
    'review_interval_days' => 90,
];