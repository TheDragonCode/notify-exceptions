<?php

namespace Helldar\Notifex\Services;

use Exception;
use Helldar\Notifex\Mail\ExceptionEmail;
use Illuminate\Support\Facades\Mail;

class Email
{
    private $handler;

    private $exception;

    public function __construct(ExceptionHandler $handler, Exception $exception)
    {
        $this->handler   = $handler;
        $this->exception = $exception;

        $mail = new ExceptionEmail($this->subject(), $this->content(), $this->file());

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

    private function file(): string
    {
        return $this->exception->getFile() . ':' . $this->exception->getLine();
    }
}
