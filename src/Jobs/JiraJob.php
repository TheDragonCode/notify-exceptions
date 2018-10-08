<?php

namespace Helldar\NotifyExceptions\Jobs;

use Helldar\NotifyExceptions\Models\ErrorNotification;
use Helldar\NotifyExceptions\Services\SlackService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JiraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable;

    protected $item;

    public function __construct(ErrorNotification $item)
    {
        $this->item = $item;
    }

    public function handle()
    {
        $this->toJira();
    }

    private function toJira()
    {

    }
}
