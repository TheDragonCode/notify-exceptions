<?php

namespace Helldar\Notifex\Jobs;

use Exception;
use Helldar\Notifex\Abstracts\JobAbstract;
use Helldar\Notifex\Traits\JobsConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExampleJob extends JobAbstract
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable, JobsConfiguration;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @var string
     */
    protected $subject;

    public function __construct(Exception $exception, string $subject)
    {
        $this->exception = $exception;
        $this->subject   = $subject;
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
