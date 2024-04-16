<?php

namespace Axn\LaravelGlide;

use Axn\LaravelGlide\Console\Commands\GlideKeyGenerate;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/glide.php', 'glide');

        $this->app->singleton('glide', fn($app) => new ServerManager($app));
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(GlideKeyGenerate::class);

            $this->publishes([
                __DIR__.'/../config/glide.php' => $this->app->configPath().'/'.'glide.php',
                __DIR__.'/../config/glide_servers/images.php' => $this->app->configPath().'/glide_servers/'.'images.php',
                __DIR__.'/../config/glide_servers/user_avatars.php' => $this->app->configPath().'/glide_servers/'.'user_avatars.php',
            ], 'glide-config');
        }
    }
}
