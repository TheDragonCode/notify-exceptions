<?php

return [
    'queue' => env('NOTIFEX_QUEUE', 'default'),

    'slack' => [
        'enabled' => env('NOTIFEX_SLACK_ENABLED', false),

        'webhook' => env('NOTIFEX_SLACK_WEBHOOK'),
    ],

    'jobs' => [
        //\Helldar\NotifyExceptions\Jobs\JiraJob::class => [
        //    'host'     => env('NOTIFEX_JIRA_HOST'),
        //    'user'     => env('NOTIFEX_JIRA_USER'),
        //    'password' => env('NOTIFEX_JIRA_PASSWORD'),
        //
        //    'project_key'   => env('NOTIFEX_JIRA_PROJECT_KEY'),
        //    'issue_type'    => env('NOTIFEX_JIRA_ISSUE_TYPE', 'Bug'),
        //    'priority_name' => env('NOTIFEX_JIRA_PRIORITY_NAME', 'Critical'),
        //],
        //
        /**
         * @see \Helldar\NotifyExceptions\Jobs\ExampleJob
         */
        //\Helldar\NotifyExceptions\Jobs\ExampleJob::class => [
        //    'host'      => 'http://127.0.0.1',
        //    'user'      => 'foo',
        //    'password'  => 'bar',
        //    'other_key' => 12345,
        //],
    ],
];
