<?php

namespace Helldar\Notifex;

use Helldar\Notifex\Console\TestException;
use Helldar\Notifex\Services\NotifyException;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public const CONFIG_PATH = __DIR__ . '/../config/notifex.php';

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'notifex');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH => config_path('notifex.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/notifex'),
            ], 'notifex-views');

            $this->loadMigrationsFrom(__DIR__ . '/../migrations');

            $this->commands([
                TestException::class,
            ]);
        }
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'notifex');

        $this->app->singleton('notifex', NotifyException::class);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function provides()
    {
        return ['notifex'];
    }
}
