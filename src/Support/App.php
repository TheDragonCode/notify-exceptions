<?php

namespace Helldar\Notifex\Support;

use Illuminate\Support\Facades\Config;

class App
{
    public function environment(): string
    {
        return Config::get('app.env');
    }

    public function name(): string
    {
        return Config::get('app.name');
    }
}
