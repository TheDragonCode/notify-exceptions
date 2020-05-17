<?php

namespace Helldar\Notifex\Services;

use Helldar\Notifex\Mail\ExceptionEmail;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Email
{
    protected $handler;

    protected $exception;

    public function __construct(ExceptionHandler $handler, Throwable $exception)
    {
        $this->handler   = $handler;
        $this->exception = $exception;

        $mail = new ExceptionEmail($this->subject(), $this->content());

        Mail::send($mail);
    }

    protected function subject(): string
    {
        return $this->handler->convertExceptionToString($this->exception);
    }

    protected function content(): string
    {
        return $this->handler->convertExceptionToHtml($this->exception);
    }
}
