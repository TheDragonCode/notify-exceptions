<?php

namespace Helldar\Notifex\Services;

use Illuminate\Support\Facades\Config;
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
    private $view;

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
        $environment = Config::get('app.env');
        $host        = app('request')->getHost() ?? Config::get('app.url');

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
    private function getFlattenedException($exception)
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
    private function decorate($content, $css, $exception)
    {
        return $this->view
            ->make('notifex::body', compact('content', 'css', 'exception'))
            ->render();
    }
}
