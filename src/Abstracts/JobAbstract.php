<?php

namespace Helldar\Notifex\Abstracts;

use Helldar\Notifex\Interfaces\JobInterface;
use Helldar\Notifex\Traits\JobsConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

abstract class JobAbstract implements JobInterface
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Notifiable;
    use JobsConfiguration;

    protected $classname;

    protected $message;

    protected $file;

    protected $line;

    protected $trace_as_string;

    public function __construct(string $classname, string $message, string $file, int $line, string $trace_as_string)
    {
        $this->classname       = $classname;
        $this->message         = $message;
        $this->file            = $file;
        $this->line            = $line;
        $this->trace_as_string = $trace_as_string;
    }

    protected function getConfig(string $class, string $key)
    {
        return Config::get(sprintf('notifex.jobs.%s.%s', $class, $key));
    }
}
