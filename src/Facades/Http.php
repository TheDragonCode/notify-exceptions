<?php

namespace DragonCode\Notifex\Facades;

use DragonCode\Notifex\Support\Http as HttpSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string url()
 * @method static string host()
 * @method static string|null userAgent()
 */
class Http extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HttpSupport::class;
    }
}
