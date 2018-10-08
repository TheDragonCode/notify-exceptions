<?php

namespace Helldar\NotifyExceptions\Jobs;

use Helldar\NotifyExceptions\Models\ErrorNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable;

    protected $item;

    public function __construct(ErrorNotification $item)
    {
        $this->item = $item;
    }

    public function handle()
    {
        $this->toMail();
    }

    /**
     * Notification of code errors in the Email.
     */
    private function toMail()
    {
        app('sneaker')->captureException($this->item->exception);
    }
}
