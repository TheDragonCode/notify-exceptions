<?php

namespace Helldar\NotifyExceptions\Services;

use Helldar\NotifyExceptions\Jobs\SlackJob;
use Helldar\NotifyExceptions\Models\ErrorNotification;

class NotifyException
{
    /**
     * @var string
     */
    private $queue;

    public function __construct()
    {
        $this->queue = config('notifex.queue', 'default');
    }

    /**
     * @param \Exception $exception
     */
    public function send($exception)
    {
        $stored = $this->store($exception);

        $this->sendEmail($stored);
        $this->sendSlack($stored);
        $this->sendJobs($stored);
    }

    /**
     * @param \Helldar\NotifyExceptions\Models\ErrorNotification $error_notification
     */
    protected function sendEmail(ErrorNotification $error_notification)
    {
        app('sneaker')->captureException($error_notification->exception);
    }

    /**
     * @param \Helldar\NotifyExceptions\Models\ErrorNotification $error_notification
     */
    protected function sendSlack(ErrorNotification $error_notification)
    {
        if (config('notifex.slack.enabled', false)) {
            SlackJob::dispatch($error_notification)
                ->onQueue($this->queue);
        }
    }

    /**
     * @param \Helldar\NotifyExceptions\Models\ErrorNotification $error_notification
     */
    protected function sendJobs(ErrorNotification $error_notification)
    {
        $jobs = (array) config('notifex.jobs', []);

        foreach ($jobs as $job => $params) {
            $job = is_numeric($job) ? $params : $job;

            if ($params['enabled'] ?? false) {
                dispatch(new $job($error_notification))
                    ->onQueue($this->queue);
            }
        }
    }

    /**
     * @param \Exception $exception
     *
     * @return \Helldar\NotifyExceptions\Models\ErrorNotification
     */
    private function store($exception): ErrorNotification
    {
        $parent = get_class($exception);

        return ErrorNotification::create(compact('parent', 'exception'));
    }
}
