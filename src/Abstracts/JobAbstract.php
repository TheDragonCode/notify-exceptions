<?php

namespace Helldar\NotifyExceptions\Abstracts;

use Helldar\NotifyExceptions\Interfaces\JobInterface;
use Helldar\NotifyExceptions\Traits\JobsConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class JobAbstract implements JobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable, JobsConfiguration;
}
