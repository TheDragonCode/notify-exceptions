<?php

namespace Helldar\Notifex\Services;

use Helldar\Notifex\Mail\ExceptionEmail;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Email
{
    private $handler;

    private $exception;

    public function __construct(ExceptionHandler $handler, Throwable $exception)
    {
        $this->handler   = $handler;
        $this->exception = $exception;

        $mail = new ExceptionEmail($this->subject(), $this->content());

        Mail::send($mail);
    }

    private function subject(): string
    {
        return $this->handler->convertExceptionToString($this->exception);
    }

    private function content(): string
    {
        return $this->handler->convertExceptionToHtml($this->exception);
    }
}
