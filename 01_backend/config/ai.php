<?php

return [
    'api_url' => env('AI_API_URL', 'http://ai-service:5000'),
    'api_key' => env('AI_API_KEY'),
    'timeout' => 30,
    'retry_times' => 3,
    'retry_delay' => 1,
    'models' => [
        'scoring' => ['version' => '2.1.0', 'path' => 'models/security_scoring_model.pkl'],
        'vulnerability' => ['version' => '1.8.0', 'path' => 'models/vulnerability_detector.h5'],
        'anomaly' => ['version' => '1.2.0', 'path' => 'models/anomaly_detector.pkl'],
        'risk' => ['version' => '2.0.0', 'path' => 'models/risk_classifier.joblib'],
    ],
    'enable_ai' => env('AI_ENABLED', true),
    'hybrid_mode' => env('AI_HYBRID_MODE', true),
];