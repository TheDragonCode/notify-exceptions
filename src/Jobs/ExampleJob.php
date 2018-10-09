<?php

namespace Helldar\NotifyExceptions\Jobs;

use Helldar\NotifyExceptions\Abstracts\JobAbstract;
use Helldar\NotifyExceptions\Models\ErrorNotification;
use Helldar\NotifyExceptions\Traits\JobsConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExampleJob extends JobAbstract
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable, JobsConfiguration;

    /**
     * @var \Helldar\NotifyExceptions\Models\ErrorNotification
     */
    protected $item;

    /**
     * JiraJob constructor.
     *
     * @param \Helldar\NotifyExceptions\Models\ErrorNotification $item
     */
    public function __construct(ErrorNotification $item)
    {
        $this->item = $item;
    }

    public function handle()
    {
        $host      = $this->getConfig('host');
        $user      = $this->getConfig('user');
        $password  = $this->getConfig('password');
        $other_key = $this->getConfig('other_key');

        // Your actions.
    }
}
