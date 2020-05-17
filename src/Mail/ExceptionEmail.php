<?php

namespace Helldar\Notifex\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ExceptionEmail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public $content;

    public function __construct(string $subject, string $content)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->queue   = $this->getQueueName();
    }

    public function build()
    {
        $this->setMailTo();
        $this->setMailFrom();

        return $this->view('notifex::raw')
            ->with('content', $this->content);
    }

    protected function setMailTo(): void
    {
        $this->to(
            Config::get('notifex.email.to')
        );
    }

    protected function setMailFrom(): void
    {
        $this->from(
            Config::get('notifex.email.from')
        );
    }

    protected function getQueueName(): string
    {
        return Config::get('notifex.queue', 'default');
    }
}
