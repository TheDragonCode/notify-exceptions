<?php

namespace Helldar\Notifex\Interfaces;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;

interface JobInterface extends ShouldQueue
{
    public function __construct(Exception $item, string $subject);

    public function handle();
}
