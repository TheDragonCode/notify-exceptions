<?php

namespace Helldar\Notifex\Console;

use Helldar\Notifex\Exceptions\NotifexTestException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Application as ConsoleApplication;

class TestException extends Command
{
    protected $signature = 'notifex:test';

    protected $description = 'Testing the mechanism for sending error notifications';

    public function handle()
    {
        try {
            $this->send(
                $this->getException()
            );

            $this->info('Notifex is working fine ✅');
        } catch (NotifexTestException $e) {
            $this->consoleApp()->renderThrowable($e, $this->output);
        }
    }

    protected function getException(): NotifexTestException
    {
        return new NotifexTestException();
    }

    protected function consoleApp(): ConsoleApplication
    {
        return new ConsoleApplication();
    }

    protected function send(NotifexTestException $e): void
    {
        app('notifex')->send($e);
    }
}
