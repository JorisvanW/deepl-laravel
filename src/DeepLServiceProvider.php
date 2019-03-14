<?php

namespace JorisvanW\DeepL\Laravel;

use JorisvanW\DeepL\Api\DeepLApiClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Laravel\Lumen\Application as LumenApplication;
use JorisvanW\DeepL\Laravel\Wrappers\DeepLApiWrapper;
use Illuminate\Foundation\Application as LaravelApplication;

/**
 * Class DeepLServiceProvider.
 */
class DeepLServiceProvider extends ServiceProvider
{
    const PACKAGE_VERSION = '0.0.1';

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/deepl.php');

        // Check if the application is a Laravel OR Lumen instance to properly merge the configuration file.
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('deepl.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('deepl');
        }

        $this->mergeConfigFrom($source, 'deepl');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApiAdapter();
    }

    /**
     * Register the DeepL API adapter class.
     *
     * @return void
     */
    protected function registerApiAdapter()
    {
        $this->app->singleton('deepl.api', function (Container $app) {
            $config = $app['config'];

            return new DeepLApiWrapper($config, $app['deepl.api.client']);
        });

        $this->app->alias('deepl.api', DeepLApiWrapper::class);
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'deepl',
            'deepl.api',
            'deepl.api.client',
        ];
    }
}
