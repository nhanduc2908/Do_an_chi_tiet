<?php

return [
    'versions' => [
        'v1' => ['name' => 'SME', 'max_users' => 10, 'max_evaluations' => 50, 'price' => 0, 'features' => ['basic_evaluation', 'basic_report']],
        'v2' => ['name' => 'Mid-market', 'max_users' => 50, 'max_evaluations' => 200, 'price' => 500, 'features' => ['advanced_scoring', 'team_evaluation']],
        'v3' => ['name' => 'Enterprise', 'max_users' => 200, 'max_evaluations' => 1000, 'price' => 2000, 'features' => ['real_time_sync', 'ai_suggestions']],
        'v4' => ['name' => 'Finance', 'max_users' => 500, 'max_evaluations' => 5000, 'price' => 5000, 'features' => ['compliance_check', 'risk_matrix']],
        'v5' => ['name' => 'Government', 'max_users' => 9999, 'max_evaluations' => 99999, 'price' => 10000, 'features' => ['dark_web_monitor', 'threat_intel']],
    ],
    'default' => 'v1',
];