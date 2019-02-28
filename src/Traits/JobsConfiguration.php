<?php

namespace Helldar\Notifex\Traits;

use Illuminate\Support\Facades\Config;

trait JobsConfiguration
{
    protected function getConfig($key)
    {
        return Config::get(sprintf('notifex.jobs.%s.%s', get_class(), $key));
    }
}
