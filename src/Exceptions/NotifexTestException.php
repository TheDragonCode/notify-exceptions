<?php

namespace Helldar\Notifex\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class NotifexTestException extends HttpException
{
    public function __construct(\Exception $previous = null)
    {
        $message = 'Notifex test exception';
        $code    = 400;

        parent::__construct($code, $message, $previous, [], $code);
    }
}
