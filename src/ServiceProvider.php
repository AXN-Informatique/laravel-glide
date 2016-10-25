<?php

namespace Axn\LaravelGlide;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('glide.server', function () {
            return new ServersManager($this->app);
        });

        $this->app->singleton('glide', function () {
            return new Glide($this->app, $this->app['glide.server']);
        });
    }
}
