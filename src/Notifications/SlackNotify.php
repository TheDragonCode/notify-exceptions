<?php

namespace Helldar\Notifex\Notifications;

use Carbon\Carbon;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

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
     * @param mixed $notifiable
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
        return (new SlackMessage)
            ->error()
            ->content($this->title)
            ->attachment(function (SlackAttachment $attachment) {
                $attachment
                    ->title($this->message)
                    ->content($this->trace_as_string)
                    ->footer(Config::get('app.name'))
                    ->timestamp(Carbon::now());
            });
    }
}
