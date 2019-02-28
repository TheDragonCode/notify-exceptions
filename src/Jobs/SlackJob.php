<?php

namespace Helldar\Notifex\Jobs;

use Helldar\Notifex\Interfaces\JobInterface;
use Helldar\Notifex\Models\ErrorNotification;
use Helldar\Notifex\Notifications\SlackNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class SlackJob implements JobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable;

    /**
     * @var \Helldar\Notifex\Models\ErrorNotification
     */
    protected $item;

    public function __construct(ErrorNotification $item)
    {
        $this->item = $item;
    }

    public function handle()
    {
        $slack = new SlackNotify($this->item->exception, $this->title());

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
        $server      = app('request')->getHost() ?? Config::get('app.url');
        $environment = Config::get('app.env');

        return implode(PHP_EOL, [
            sprintf('*%s | Server - %s | Environment - %s*', $this->item->parent, $server, $environment),
            sprintf('`%s:%s`', $this->item->exception->getFile(), $this->item->exception->getLine()),
        ]);
    }
}
