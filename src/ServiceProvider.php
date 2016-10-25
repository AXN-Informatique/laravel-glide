<?php

namespace Axn\LaravelGlide;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
        $this->mergeConfigFrom(__DIR__ . '/../config/glide.php', 'glide');

        $this->app->singleton('glide.server', function () {
            return new ServersManager($this->app);
        });

        $this->app->singleton('glide', function () {
            return new Glide($this->app, $this->app['glide.server']);
        });
    }

    public function provides()
    {
        return ['glide', 'glide.server'];
    }
}
