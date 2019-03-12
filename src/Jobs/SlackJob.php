<?php

namespace Helldar\Notifex\Jobs;

use Exception;
use Helldar\Notifex\Abstracts\JobAbstract;
use Helldar\Notifex\Notifications\SlackNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class SlackJob extends JobAbstract
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable;

    protected $exception;

    public function __construct(Exception $exception, string $subject)
    {
        $this->exception = $exception;
    }

    public function handle()
    {
        $slack = new SlackNotify($this->exception, $this->title());

        $this->notify($slack);
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return Config::get('notifex.slack.webhook');
    }

    private function title()
    {
        $host        = app('request')->getHost() ?? Config::get('app.url');
        $environment = Config::get('app.env');

        return implode(PHP_EOL, [
            sprintf('*%s | %s | %s*', $environment, $host, class_basename($this->exception)),
            sprintf('`%s:%s`', $this->exception->getFile(), $this->exception->getLine()),
        ]);
    }
}
