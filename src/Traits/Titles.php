<?php

namespace Helldar\NotifyExceptions\Traits;

trait Titles
{
    private function title()
    {
        $server      = request()->getHost() ?? config('app.url');
        $environment = config('app.env');

        return implode(PHP_EOL, [
            sprintf('*%s | Server - %s | Environment - %s*', $this->item->parent, $server, $environment),
            sprintf('`%s:%s`', $this->item->exception->getFile(), $this->item->exception->getLine()),
        ]);
    }
}
