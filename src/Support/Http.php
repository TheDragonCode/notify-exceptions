<?php

namespace DragonCode\Notifex\Support;

use Illuminate\Support\Facades\Config;

class Http
{
    public function host(): string
    {
        return parse_url($this->url(), PHP_URL_HOST);
    }

    public function url(): string
    {
        return app('request')->url() ?? Config::get('app.url') ?: 'http://localhost';
    }

    public function userAgent(): ?string
    {
        return app('request')->userAgent() ?? null;
    }
}
