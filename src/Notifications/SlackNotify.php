<?php

namespace Helldar\Notifex\Notifications;

use Carbon\Carbon;
use DateTimeInterface;
use Helldar\Notifex\Facades\App;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackNotify extends Notification
{
    protected $title;

    protected $message;

    protected $trace_as_string;

    public function __construct(string $title, string $message, string $trace_as_string)
    {
        $this->title           = $title;
        $this->message         = $message;
        $this->trace_as_string = $trace_as_string;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param $notifiable
     *
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return $this->message()
            ->error()
            ->content($this->title)
            ->attachment(function (SlackAttachment $attachment) {
                $attachment
                    ->title($this->message)
                    ->content($this->trace_as_string)
                    ->footer($this->appName())
                    ->timestamp($this->currentTime());
            });
    }

    protected function message(): SlackMessage
    {
        return new SlackMessage();
    }

    protected function appName(): string
    {
        return App::name();
    }

    private function currentTime(): DateTimeInterface
    {
        return Carbon::now();
    }
}
