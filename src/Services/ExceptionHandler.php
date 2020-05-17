<?php

namespace Helldar\Notifex\Services;

use Helldar\Notifex\Facades\App;
use Helldar\Notifex\Facades\Http;
use Illuminate\View\Factory;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class ExceptionHandler
{
    /**
     * The view factory implementation.
     *
     * @var \Illuminate\View\Factory
     */
    protected $view;

    /**
     * Create a new exception handler instance.
     *
     * @param  \Illuminate\View\Factory  $view
     */
    public function __construct(Factory $view)
    {
        $this->view = $view;
    }

    /**
     * Create a string for the given exception.
     *
     * @param  \Exception|\Throwable  $exception
     *
     * @return string
     */
    public function convertExceptionToString($exception)
    {
        $environment = $this->environment();
        $host        = $this->host();

        return $this->view
            ->make('notifex::subject', compact('exception', 'environment', 'host'))
            ->render();
    }

    /**
     * Create a html for the given exception.
     *
     * @param  \Exception|\Throwable  $exception
     *
     * @return string
     */
    public function convertExceptionToHtml($exception)
    {
        $flat    = $this->getFlattenedException($exception);
        $handler = new SymfonyExceptionHandler();

        return $this->decorate($handler->getContent($flat), $handler->getStylesheet($flat), $flat);
    }

    /**
     * Converts the Exception in a PHP Exception to be able to serialize it.
     *
     * @param  \Exception|\Throwable  $exception
     *
     * @return \Symfony\Component\Debug\Exception\FlattenException
     */
    protected function getFlattenedException($exception)
    {
        if (! $exception instanceof FlattenException) {
            $exception = FlattenException::create($exception);
        }

        return $exception;
    }

    /**
     * Get the html response content.
     *
     * @param  string  $content
     * @param  string  $css
     * @param  \Exception|\Throwable  $exception
     *
     * @return string
     */
    protected function decorate($content, $css, $exception)
    {
        return $this->view
            ->make('notifex::body', compact('content', 'css', 'exception'))
            ->render();
    }

    protected function environment(): string
    {
        return App::environment();
    }

    protected function host(): string
    {
        return Http::host();
    }
}
