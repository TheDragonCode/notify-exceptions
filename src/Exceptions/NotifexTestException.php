<?php

namespace DragonCode\Notifex\Exceptions;

use Exception;

class NotifexTestException extends Exception
{
    public function __construct()
    {
        parent::__construct('Notifex test exception', 400);
    }
}
