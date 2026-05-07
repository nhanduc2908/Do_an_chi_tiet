<?php

return [
    'standards' => [
        'iso27001' => [
            'name' => 'ISO 27001',
            'threshold' => 80,
            'controls' => 114,
        ],
        'soc2' => [
            'name' => 'SOC 2',
            'threshold' => 75,
            'controls' => 64,
        ],
        'gdpr' => [
            'name' => 'GDPR',
            'threshold' => 85,
            'controls' => 99,
        ],
        'hipaa' => [
            'name' => 'HIPAA',
            'threshold' => 90,
            'controls' => 58,
        ],
        'pci_dss' => [
            'name' => 'PCI DSS',
            'threshold' => 95,
            'controls' => 328,
        ],
    ],
    'auto_check' => env('COMPLIANCE_AUTO_CHECK', true),
    'check_interval' => env('COMPLIANCE_CHECK_INTERVAL', 7),
];