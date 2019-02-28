<?php

namespace Helldar\Notifex\Notifications;

use Carbon\Carbon;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class SlackNotify extends Notification
{
    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @var string
     */
    protected $title;

    /**
     * Create a new notification instance.
     *
     * @param $exception
     * @param $title
     */
    public function __construct($exception, $title)
    {
        $this->exception = $exception;
        $this->title     = $title;
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
                    ->title($this->exception->getMessage())
                    ->content($this->exception->getTraceAsString())
                    ->footer(Config::get('app.name'))
                    ->timestamp(Carbon::now());
            });
    }
}
