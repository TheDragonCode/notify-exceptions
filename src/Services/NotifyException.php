<?php

namespace Helldar\NotifyExceptions\Services;

use Helldar\NotifyExceptions\Jobs\JiraJob;
use Helldar\NotifyExceptions\Jobs\SlackJob;
use Helldar\NotifyExceptions\Models\ErrorNotification;

class NotifyException
{
    private $queue;

    public function __construct()
    {
        $this->queue = config('notifex.queue', 'default');
    }

    public function send($exception)
    {
        $stored = $this->store($exception);

        $this->sendEmail($stored);
        $this->sendSlack($stored);
        $this->sendJira($stored);
    }

    protected function sendEmail(ErrorNotification $error_notification)
    {
        app('sneaker')->captureException($error_notification->exception);
    }

    protected function sendSlack(ErrorNotification $error_notification)
    {
        if (config('notifex.slack.enabled', false)) {
            SlackJob::dispatch($error_notification)
                ->onQueue($this->queue);
        }
    }

    protected function sendJira(ErrorNotification $error_notification)
    {
        if (config('notifex.jira.enabled', false)) {
            JiraJob::dispatch($error_notification)
                ->onQueue($this->queue);
        }
    }

    private function store($exception): ErrorNotification
    {
        $parent = get_class($exception);

        return ErrorNotification::create(compact('parent', 'exception'));
    }
}
