<?php

namespace RichPeers\LaravelLogOAuth2Curl;

use Illuminate\Support\ServiceProvider;
use RichPeers\LaravelLogOAuth2Curl\OAuth2\ClientCredentialsGrantToken;
use Illuminate\Contracts\Container\Container;
use Monolog\Logger;
use RichPeers\LaravelLogOAuth2Curl\Monolog\LaravelLogOAuth2CurlHandler;

class LaravelLogOAuth2CurlProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->getConfigFilePath() => config_path('logserver.php'),
        ], 'logserver');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeDefaultConfig();

        $this->registerClientCredentialsGrantToken();

        $this->extendLaravelLogManager();
    }

    /**
     * Merge default config
     */
    protected function mergeDefaultConfig()
    {
        $this->mergeConfigFrom(
            $this->getConfigFilePath(), 'logserver'
        );
    }

    /**
     * Register ClientCredentialsGrantToken.
     */
    protected function registerClientCredentialsGrantToken()
    {
        $this->app->singleton(ClientCredentialsGrantToken::class, function (Container $app) {
            return (new ClientCredentialsGrantToken($app['cache'], config('logserver')));
        });
    }

    /**
     * Extend LaravelLogManager with new driver.
     */
    protected function extendLaravelLogManager()
    {
        $this->app['log']->extend('logserver', function (Container $app, array $config) {

            $logger = new Logger('logserver');
            $handler = new LaravelLogOAuth2CurlHandler(config('logserver'));

            $logger->pushHandler($handler);

            return $logger;
        });
    }

    /**
     * Get path to config file.
     * @return string
     */
    protected function getConfigFilePath()
    {
        return __DIR__ . '/../config/logserver.php';
    }
}
