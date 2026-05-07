<?php

return [
    'available' => [
        'slack' => ['name' => 'Slack', 'enabled' => true],
        'teams' => ['name' => 'Microsoft Teams', 'enabled' => true],
        'telegram' => ['name' => 'Telegram', 'enabled' => false],
        'discord' => ['name' => 'Discord', 'enabled' => false],
        'zapier' => ['name' => 'Zapier', 'enabled' => true],
        'make' => ['name' => 'Make', 'enabled' => true],
        'jira' => ['name' => 'Jira', 'enabled' => true],
        'servicenow' => ['name' => 'ServiceNow', 'enabled' => false],
    ],
    'webhook_timeout' => 30,
    'batch_size' => 50,
    'sync_interval' => 300,
];