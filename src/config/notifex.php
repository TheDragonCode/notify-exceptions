<?php

return [
    'queue' => env('NOTIFEX_QUEUE', 'default'),

    'email' => [
        'enabled' => env('NOTIFEX_EMAIL_ENABLED', true),
    ],

    'slack' => [
        'enabled' => env('NOTIFEX_SLACK_ENABLED', false),

        'webhook' => env('NOTIFEX_SLACK_WEBHOOK'),
    ],

    'jira' => [
        'enabled' => env('NOTIFEX_JIRA_ENABLED', false),

        'host'     => env('NOTIFEX_JIRA_HOST'),
        'user'     => env('NOTIFEX_JIRA_USER'),
        'password' => env('NOTIFEX_JIRA_PASSWORD'),
    ],
];
