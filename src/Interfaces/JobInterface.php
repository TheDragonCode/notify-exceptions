<?php

namespace Helldar\Notifex\Interfaces;

use Helldar\Notifex\Models\ErrorNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

interface JobInterface extends ShouldQueue
{
    public function __construct(ErrorNotification $item);

    public function handle();
}
