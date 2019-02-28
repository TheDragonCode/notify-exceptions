<?php

namespace Helldar\NotifyExceptions\Mail;

use Helldar\NotifyExceptions\Models\ErrorNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class ExceptionEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $item;

    public $queue;

    public function __construct(ErrorNotification $notification)
    {
        $this->item = $notification;

        $this->queue = Config::get('notifex.queue');
    }

    public function build()
    {
        $this->from(Config::get('notifex.email.from'));
        $this->to(Config::get('notifex.email.to'));
        $this->subject($this->title());

        $exception = $this->getFlattenedException();

        $handler = new SymfonyExceptionHandler;

        $content = $handler->getContent($exception);

        $css = $handler->getStylesheet($exception);

        return $this->markdown('notifex::error', compact('content', 'css', 'exception'));
    }

    private function title()
    {
        $parent      = $this->item->parent;
        $host        = request()->getHost() ?? Config::get('app.url');
        $environment = config('app.env');

        return sprintf('[notifex] %s | %s | %s', $environment, $host, $parent);
    }

    private function getFlattenedException()
    {
        if (!$this->item->exception instanceof FlattenException) {
            return FlattenException::create($this->item->exception);
        }

        return $this->item->exception;
    }
}
