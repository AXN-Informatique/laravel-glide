<?php

namespace Axn\LaravelGlide;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Axn\LaravelGlide\Console\Commands\GlideKeyGenerate;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/glide.php' => $this->app->configPath() . '/' . 'glide.php',
        ], 'config');
    }

    public function register()
    {
        $this->commands(GlideKeyGenerate::class);

        $this->mergeConfigFrom(__DIR__ . '/../config/glide.php', 'glide');

        // The server manager is used to resolve various servers, since multiple
        // servers might be managed.
        $this->app->singleton('glide', function ($app) {
            return new ServerManager($app);
        });
    }

    public function provides()
    {
        return ['glide'];
    }
}
