<?php

return [
    'formats' => [
        'pdf' => ['driver' => 'dompdf', 'enabled' => true],
        'excel' => ['driver' => 'phpspreadsheet', 'enabled' => true],
        'csv' => ['driver' => 'native', 'enabled' => true],
        'json' => ['driver' => 'native', 'enabled' => true],
    ],
    'max_records' => 10000,
    'generation_timeout' => 300,
    'storage_days' => 90,
    'default_format' => 'pdf',
    'chart_colors' => ['#00CC00', '#0099FF', '#FFCC00', '#FF6600', '#FF0000', '#9900CC', '#00CCCC'],
    'templates_path' => storage_path('app/report_templates'),
];