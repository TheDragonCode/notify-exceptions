<?php

namespace Helldar\NotifyExceptions\Jobs;

use Helldar\NotifyExceptions\Interfaces\JobInterface;
use Helldar\NotifyExceptions\Models\ErrorNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class EmailJob implements JobInterface
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
        $this->toMail();
    }

    public function toMail()
    {
        $exception = $this->item->exception;

        return (new MailMessage)
            ->error()
            ->subject($this->title())
            ->markdown('mail.error', compact('exception'));
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification $notification
     *
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return Config::get('notifex.email.to');
    }

    private function title()
    {
        $server      = request()->getHost() ?? config('app.url');
        $environment = config('app.env');

        return implode(PHP_EOL, [
            sprintf('*%s | Server - %s | Environment - %s*', $this->item->parent, $server, $environment),
            sprintf('`%s:%s`', $this->item->exception->getFile(), $this->item->exception->getLine()),
        ]);
    }
}
