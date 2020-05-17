<?php

return [
    'enabled' => env('NOTIFEX_ENABLED', true),

    'queue' => env('NOTIFEX_QUEUE', 'default'),

    'ignore_bots' => true,

    'email' => [
        'enabled' => env('NOTIFEX_EMAIL_ENABLED', true),

        'from' => env('NOTIFEX_EMAIL_FROM', 'example@example.com'),

        'to' => env('NOTIFEX_EMAIL_TO', 'example@example.com'),
    ],

    'slack' => [
        'enabled' => env('NOTIFEX_SLACK_ENABLED', false),

        'webhook' => env('NOTIFEX_SLACK_WEBHOOK'),
    ],

    'jobs' => [
        //\Helldar\Notifex\Jobs\JiraJob::class => [
        //    'enabled' => env('NOTIFEX_JIRA_ENABLED', false),
        //
        //    'host'     => env('NOTIFEX_JIRA_HOST'),
        //    'user'     => env('NOTIFEX_JIRA_USER'),
        //    'password' => env('NOTIFEX_JIRA_PASSWORD'),
        //
        //    'project_key'   => env('NOTIFEX_JIRA_PROJECT_KEY'),
        //    'issue_type'    => env('NOTIFEX_JIRA_ISSUE_TYPE', 'Bug'),
        //    'priority_name' => env('NOTIFEX_JIRA_PRIORITY_NAME', 'Critical'),
        //],
        //
        /*
         * @see \Helldar\Notifex\Jobs\ExampleJob
         */
        //\Helldar\Notifex\Jobs\ExampleJob::class => [
        //    'host'      => env('EXAMPLE_HOST'), // http://127.0.0.1:8080
        //    'user'      => env('EXAMPLE_USER'), // 'foo'
        //    'password'  => env('EXAMPLE_PASS'), // 'bar'
        //    'other_key' => env('EXAMPLE_OTHER_KEY'), // 12345
        //],
    ],
];
