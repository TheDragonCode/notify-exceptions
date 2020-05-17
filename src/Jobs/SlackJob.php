<?php

namespace Helldar\Notifex\Jobs;

use Helldar\Notifex\Abstracts\JobAbstract;
use Helldar\Notifex\Notifications\SlackNotify;

class SlackJob extends JobAbstract
{
    public function handle()
    {
        $this->notify(
            $this->getSlackNotify()
        );
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
        return implode(PHP_EOL, [
            sprintf('*%s | %s | %s*', $this->environment(), $this->host(), $this->classname()),
            sprintf('`%s:%s`', $this->file, $this->line),
        ]);
    }

    protected function getSlackNotify(): SlackNotify
    {
        return new SlackNotify($this->title(), $this->message, $this->trace_as_string);
    }
}
