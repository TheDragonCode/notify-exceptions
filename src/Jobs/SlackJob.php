<?php

namespace Helldar\Notifex\Jobs;

use Helldar\Notifex\Abstracts\JobAbstract;
use Helldar\Notifex\Notifications\SlackNotify;
use Illuminate\Support\Facades\Config;

class SlackJob extends JobAbstract
{
    public function handle()
    {
        $slack = new SlackNotify($this->title(), $this->message, $this->trace_as_string);

        $this->notify($slack);
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return string
     */
    public function routeNotificationForSlack($notification): ?string
    {
        return $this->getConfig(get_class(), 'webhook');
    }

    protected function title(): string
    {
        $host        = app('request')->getHost() ?? Config::get('app.url');
        $environment = Config::get('app.env');

        return implode(PHP_EOL, [
            sprintf('*%s | %s | %s*', $environment, $host, class_basename($this->classname)),
            sprintf('`%s:%s`', $this->file, $this->line),
        ]);
    }
}
