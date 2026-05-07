<?php

return [
    'host' => env('WEBSOCKET_HOST', '0.0.0.0'),
    'port' => env('WEBSOCKET_PORT', 6001),
    'app_id' => env('WEBSOCKET_APP_ID'),
    'app_key' => env('WEBSOCKET_APP_KEY'),
    'app_secret' => env('WEBSOCKET_APP_SECRET'),
    'cluster' => env('WEBSOCKET_CLUSTER'),
    'encrypted' => env('WEBSOCKET_ENCRYPTED', true),
];