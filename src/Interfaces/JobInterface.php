<?php

namespace Helldar\NotifyExceptions\Interfaces;

use Helldar\NotifyExceptions\Models\ErrorNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

interface  JobInterface extends ShouldQueue
{
    public function __construct(ErrorNotification $item);

    public function handle();
}
