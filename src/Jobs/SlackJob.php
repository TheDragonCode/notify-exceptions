<?php

namespace Helldar\NotifyExceptions\Jobs;

use Helldar\NotifyExceptions\Models\ErrorNotification;
use Helldar\NotifyExceptions\Notifications\SlackNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SlackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable;

    /**
     * @var \Helldar\NotifyExceptions\Models\ErrorNotification
     */
    protected $item;

    public function __construct(ErrorNotification $item)
    {
        $this->item = $item;
    }

    public function handle()
    {
        $slack = new SlackNotify($this->item->exception, $this->titleForSlack());

        $this->notify($slack);
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification $notification
     *
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return config('notifex.slack.webhook');
    }

    private function titleForSlack()
    {
        $server      = request()->getHost() ?? config('app.url');
        $environment = config('app.env');

        return implode(PHP_EOL, [
            sprintf('*%s | Server - %s | Environment - %s*', $this->item->parent, $server, $environment),
            sprintf('`%s:%s`', $this->item->exception->getFile(), $this->item->exception->getLine()),
        ]);
    }
}
