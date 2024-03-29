<?php

namespace DragonCode\Notifex;

use DragonCode\Notifex\Console\TestException;
use DragonCode\Notifex\Services\NotifyException;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public const CONFIG_PATH = __DIR__ . '/../config/notifex.php';

    public const VIEWS_PATH = __DIR__ . '/../resources/views';

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->loadViewsFrom(self::VIEWS_PATH, 'notifex');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH => $this->app->configPath('notifex.php'),
            ], 'config');

            $this->publishes([
                self::VIEWS_PATH => $this->app->resourcePath('views/vendor/notifex'),
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
