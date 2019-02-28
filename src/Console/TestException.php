<?php

namespace Helldar\NotifyExceptions\Console;

use Helldar\NotifyExceptions\Exceptions\NotifexTestException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Application as ConsoleApplication;

class TestException extends Command
{
    protected $signature = 'notifex:test';

    protected $description = 'Testing the mechanism for sending error notifications';

    public function handle()
    {
        try {
            app('notifex')->send(new NotifexTestException);

            $this->info('Notifex is working fine ✅');
        } catch (NotifexTestException $exception) {
            (new ConsoleApplication)->renderException($exception, $this->output);
        }
    }
}
