<?php

return [
    'domain' => [
        'front' => env('APP_FRONT_DOMAIN'),
        'admin' => env('APP_ADMIN_DOMAIN'),
    ],

    'copyright' => [
        'name' => 'Central Bureau Of Statistics',
        'url'  => 'https://cbs.gov.np/',
    ],

    'developer' => [
        'name' => 'YoungInnovations',
        'url'  => 'https://younginnovations.com.np/',
    ],

    'application-stage'             => env('APPLICATION_STAGE', 'application'),
    'default-locale'                => env('APP_LOCALE'),
    'deadline'                      => env('APPLICATION_DEADLINE'),
    'editable-days'                 => env('EDITABLE_DAYS', 7),
    'number-of-assignments'         => env('NUMBER_OF_ASSIGNMENTS', 100),
    'offline-application-available' => env('OFFLINE_APPLICATION_AVAILABLE', false),
    'shortlist-available'           => env('SHORTLIST_AVAILABLE', false),
    'show-notice'                   => env('SHOW_NOTICE', false),

    'age-limit' => [
        'female' => [18, 45],
        'male'   => [18, 40],
        'other'  => [18, 40],
    ],

    'gtm_container_id'  => env('GTM_CONTAINER_ID', ''),
    'log_horizon'       => env('LOG_HORIZON', false),
    'slack_webhook_url' => env('LOG_SLACK_WEBHOOK_URL', ''),
    'slack_channel'     => env('LOG_SLACK_CHANNEL', ''),
];
