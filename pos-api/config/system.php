<?php

return [
    'default_password' => env('DEFAULT_PASSWORD', null),

    'cache_expiration' => fn() => now()->addMonths(6),

    'currency' => env("CURRENCY", null),

    'is_base_url' => env("IS_BASE_URL"),

    'frontend_url' => env("FRONTEND_URL"),

    'is_access_token' => env("IS_ACCESS_TOKEN"),

    /**
     * Define api end points
     */
    'is_api_endpoint' => [
        'order' => [
            'create_notification' => '/users/%s/notifications/order/create'
        ]
    ],

    /**
     * Define the allowed IPs for the application.
     */
    'ip_restriction' => [
        'allowed_ips' => explode(',', env('ALLOWED_IP', ''))
    ],
];
