<?php

return [
    'max_devices_per_user' => 5,
    'session_timeout' => 3600,
    'sync_interval' => 300,
    'heartbeat_interval' => 60,
    'device_types' => [
        'web' => ['name' => 'Web Browser', 'icon' => 'globe'],
        'android' => ['name' => 'Android', 'icon' => 'android'],
        'ios' => ['name' => 'iOS', 'icon' => 'apple'],
        'desktop' => ['name' => 'Desktop', 'icon' => 'monitor'],
    ],
    'trusted_devices_enabled' => true,
    'trust_duration_days' => 30,
    'push_notification_enabled' => true,
];