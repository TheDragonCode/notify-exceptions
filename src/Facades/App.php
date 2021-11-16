<?php

namespace DragonCode\Notifex\Facades;

use DragonCode\Notifex\Support\App as AppSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string environment()
 * @method static string name()
 */
class App extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AppSupport::class;
    }
}
