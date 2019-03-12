<?php

namespace Helldar\Notifex\Traits;

use Illuminate\Support\Facades\Config;

trait JobsConfiguration
{
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 10;

    protected function getConfig($key)
    {
        return Config::get(sprintf('notifex.jobs.%s.%s', get_class(), $key));
    }
}
