<?php

return [
    'sources' => [
        'cve' => ['name' => 'CVE Database', 'enabled' => true, 'url' => 'https://cve.circl.lu'],
        'nvd' => ['name' => 'NVD', 'enabled' => true, 'url' => 'https://services.nvd.nist.gov'],
        'exploit_db' => ['name' => 'Exploit DB', 'enabled' => true, 'url' => 'https://www.exploit-db.com'],
        'dark_web' => ['name' => 'Dark Web Monitor', 'enabled' => false],
        'social_media' => ['name' => 'Social Media', 'enabled' => false],
    ],
    'update_interval' => 3600,
    'retention_days' => 30,
    'auto_block_threats' => true,
    'threat_score_threshold' => 70,
];