<?php

namespace Helldar\Notifex\Jobs;

use Helldar\Notifex\Abstracts\JobAbstract;

class ExampleJob extends JobAbstract
{
    public function handle()
    {
        $host      = $this->config('host');
        $user      = $this->config('user');
        $password  = $this->config('password');
        $other_key = $this->config('other_key');

        // Your actions.
        //
        // $classname       = $this->classname;
        // $class_basename  = class_basename($this->classname);
        // $message         = $this->message;
        // $file            = $this->file;
        // $line            = $this->line;
        // $trace_as_string = $this->trace_as_string;
    }

    protected function config(string $key)
    {
        return $this->getConfig(get_class(), $key);
    }
}
