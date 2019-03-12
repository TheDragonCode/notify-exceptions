<?php

namespace Helldar\Notifex\Interfaces;

use Illuminate\Contracts\Queue\ShouldQueue;

interface JobInterface extends ShouldQueue
{
    public function __construct(string $classname, string $message, string $file, int $line, string $trace_as_string);

    public function handle();
}
