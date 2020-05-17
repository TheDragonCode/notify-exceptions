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

        $this->send(
            $this->getException()
        );
    }

    protected function subject(): string
    {
        return $this->handler->convertExceptionToString($this->exception);
    }

    protected function content(): string
    {
        return $this->handler->convertExceptionToHtml($this->exception);
    }

    protected function getException(): ExceptionEmail
    {
        return new ExceptionEmail($this->subject(), $this->content());
    }

    protected function send(ExceptionEmail $e): void
    {
        Mail::send($e);
    }
}
