<?php

namespace Helldar\Notifex\Services;

use Helldar\Notifex\Jobs\SlackJob;
use Helldar\Notifex\Mail\ExceptionEmail;
use Helldar\Notifex\Models\ErrorNotification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

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
        try {
            if ($this->isIgnore()) {
                return;
            }

            $stored = $this->store($exception);

            $this->sendEmail($stored);
            $this->sendSlack($stored);
            $this->sendJobs($stored);
        } catch (\Exception $exception) {
        }
    }

    /**
     * @param \Helldar\Notifex\Models\ErrorNotification $error_notification
     */
    protected function sendEmail(ErrorNotification $error_notification)
    {
        if (config('notifex.email.enabled', true)) {
            $mail = new ExceptionEmail($error_notification);

            Mail::send($mail);
        }
    }

    /**
     * @param \Helldar\Notifex\Models\ErrorNotification $error_notification
     */
    protected function sendSlack(ErrorNotification $error_notification)
    {
        if (config('notifex.slack.enabled', false)) {
            SlackJob::dispatch($error_notification)
                ->onQueue($this->queue);
        }
    }

    /**
     * @param \Helldar\Notifex\Models\ErrorNotification $error_notification
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
     * @return \Helldar\Notifex\Models\ErrorNotification
     */
    private function store($exception): ErrorNotification
    {
        $parent = get_class($exception);

        return ErrorNotification::create(compact('parent', 'exception'));
    }

    private function isIgnore(): bool
    {
        $ignore_bots = Config::get('notifex.ignore_bots', true);

        if (!$ignore_bots) {
            return false;
        }

        $crawler = new CrawlerDetect;

        return $crawler->isCrawler($this->userAgent());
    }

    private function userAgent(): ?string
    {
        try {
            return request()->userAgent();
        } catch (\Exception $exception) {
            return null;
        }
    }
}
