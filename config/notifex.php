<?php

return [
    // Determines whether sending error messages is enabled.

    'enabled' => env('NOTIFEX_ENABLED', true),

    // Specifies the name of the queue into which to write an error event for dispatch.

    'queue' => env('NOTIFEX_QUEUE', 'default'),

    // Determines whether to ignore messages from bots.

    'ignore_bots' => true,
    // Determination of settings and status of sending to email.

    'email' => [
        // Determines if sending to email is allowed.

        'enabled' => env('NOTIFEX_EMAIL_ENABLED', true),

        // Sets the sender's address.

        'from' => env('NOTIFEX_EMAIL_FROM', 'example@example.com'),

        // Sets the recipient's address.

        'to' => env('NOTIFEX_EMAIL_TO', 'example@example.com'),
    ],

    'slack' => [
        // Determines if sending to slack channel is allowed.

        'enabled' => env('NOTIFEX_SLACK_ENABLED', false),

        // Sets the webhook address for sending a message.

        'webhook' => env('NOTIFEX_SLACK_WEBHOOK'),

        /*
         * If specified, the name will be used, otherwise by default.
         *
         * Available:
         *   ['Ghost', ':ghost:']
         *   ['Ghost']
         *   null
         *
         * By default, null
         */

        'from' => null,

        /*
         * If specified, notifications will be sent to the channel.
         *
         * By default, null.
         */

        'to' => null,
    ],

    'jobs' => [
        // \DragonCode\Notifex\Jobs\JiraJob::class => [
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
        // @see \DragonCode\Notifex\Jobs\ExampleJob
        // \DragonCode\Notifex\Jobs\ExampleJob::class => [
        //    'host'      => env('EXAMPLE_HOST'), // http://127.0.0.1:8080
        //    'user'      => env('EXAMPLE_USER'), // 'foo'
        //    'password'  => env('EXAMPLE_PASS'), // 'bar'
        //    'other_key' => env('EXAMPLE_OTHER_KEY'), // 12345
        //],
    ],
];
