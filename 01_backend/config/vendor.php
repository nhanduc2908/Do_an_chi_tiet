<?php

return [
    'risk_levels' => [
        'low' => ['level' => 1, 'color' => '#00CC00', 'review_frequency' => 365],
        'medium' => ['level' => 2, 'color' => '#FFCC00', 'review_frequency' => 180],
        'high' => ['level' => 3, 'color' => '#FF6600', 'review_frequency' => 90],
        'critical' => ['level' => 4, 'color' => '#FF0000', 'review_frequency' => 30],
    ],
    'assessment_criteria' => [
        'security' => 0.35,
        'compliance' => 0.25,
        'financial' => 0.20,
        'operational' => 0.20,
    ],
    'auto_assessment_enabled' => true,
    'reminder_days_before_expiry' => [30, 14, 7],
];