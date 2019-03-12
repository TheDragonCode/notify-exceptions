<?php

namespace Helldar\Notifex\Services;

use Exception;
use Helldar\Notifex\Jobs\SlackJob;
use Helldar\Notifex\Mail\ExceptionEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Psr\Log\LoggerInterface;

class NotifyException
{
    /**
     * @var string
     */
    private $queue;

    /**
     * The exception handler implementation.
     *
     * @var \Helldar\Notifex\Services\ExceptionHandler
     */
    private $handler;

    /**
     * The log writer implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Exception
     */
    private $exception;

    public function __construct(ExceptionHandler $handler, LoggerInterface $logger)
    {
        $this->queue = Config::get('notifex.queue', 'default');

        $this->handler = $handler;
        $this->logger  = $logger;
    }

    /**
     * @param \Exception $exception
     */
    public function send($exception)
    {
        try {
            if ($this->isIgnoreBots() || !$this->isEnabled()) {
                return;
            }

            $this->exception = $exception;

            $this->sendEmail();
            $this->sendSlack();
            $this->sendJobs();
        } catch (Exception $exception) {
            $this->logger->error(sprintf(
                'Exception thrown in Notifex when capturing an exception (%s: %s)',
                get_class($exception), $exception->getMessage()
            ));

            $this->logger->error($exception);
        }
    }

    protected function sendEmail()
    {
        if (Config::get('notifex.email.enabled', true)) {
            $mail = new ExceptionEmail($this->getSubject(), $this->getContent());

            Mail::send($mail);
        }
    }

    protected function sendSlack()
    {
        if (Config::get('notifex.slack.enabled', false)) {
            SlackJob::dispatch($this->exception, $this->getSubject())
                ->onQueue($this->queue);
        }
    }

    protected function sendJobs()
    {
        $jobs = (array) Config::get('notifex.jobs', []);

        foreach ($jobs as $job => $params) {
            if ($params['enabled'] ?? false) {
                $job = is_numeric($job) ? $params : $job;

                dispatch(new $job($this->exception, $this->getSubject()))
                    ->onQueue($this->queue);
            }
        }
    }

    private function isIgnoreBots(): bool
    {
        $ignore_bots = Config::get('notifex.ignore_bots', true);

        if (!$ignore_bots) {
            return false;
        }

        $crawler = new CrawlerDetect;

        return $crawler->isCrawler($this->userAgent());
    }

    private function isEnabled()
    {
        $email = Config::get('notifex.email.enabled', true);
        $slack = Config::get('notifex.slack.enabled', false);

        $jobs = array_filter(Config::get('notifex.jobs', []), function ($item) {
            return $item['enabled'] ?? false == true;
        });

        return $email == true || $slack == true || sizeof($jobs) > 0;
    }

    private function userAgent(): ?string
    {
        try {
            return app('request')->userAgent();
        } catch (\Exception $exception) {
            return null;
        }
    }

    private function getSubject()
    {
        return $this->handler->convertExceptionToString($this->exception);
    }

    private function getContent()
    {
        return $this->handler->convertExceptionToHtml($this->exception);
    }
}
