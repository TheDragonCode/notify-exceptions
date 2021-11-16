<?php

namespace DragonCode\Notifex\Notifications;

use Carbon\Carbon;
use DateTimeInterface;
use DragonCode\Notifex\Facades\App;
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
        return $this->prepareMessage($this->message())
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

    protected function prepareMessage(SlackMessage $message): SlackMessage
    {
        $this->from($message);
        $this->to($message);

        return $message;
    }

    protected function appName(): string
    {
        return App::name();
    }

    protected function from(SlackMessage $message)
    {
        if ($from = Config::get('notifex.slack.from')) {
            if (count($from) === 2) {
                [$username, $icon] = $from;
            } elseif (count($from) === 1) {
                [$username] = $from;
            }

            if ($username ?? false) {
                $message->from($username, $icon ?? null);
            }
        }
    }

    protected function to(SlackMessage $message)
    {
        if ($to = Config::get('notifex.slack.to')) {
            $channel = ltrim($to, '#');

            $message->to('#' . $channel);
        }
    }

    protected function currentTime(): DateTimeInterface
    {
        return Carbon::now();
    }
}
