<?php

namespace Helldar\NotifyExceptions\Traits;

trait JobsConfiguration
{
    protected function getConfig($key)
    {
        return config(sprintf('notifex.jobs.%s.%s', get_class(), $key));
    }
}
