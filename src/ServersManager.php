<?php

namespace Axn\LaravelGlide;

use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;

class ServersManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The array of instanciated Glide servers.
     *
     * @var array
     */
    protected $servers = [];

    /**
     * Create a new servers manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return voide
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get a server instance.
     *
     * @param  string  $name
     * @return \League\Glide\Server
     */
    public function server($name = null)
    {
        $name = $name ?: $this->getDefaultServer();

        return $this->get($name)['instance'];
    }

    /**
     * Get a server configuration.
     *
     * @param  string  $name
     * @return array
     */
    public function config($name = null)
    {
        $name = $name ?: $this->getDefaultServer();

        return $this->get($name)['config'];
    }

    /**
     * Attempt to get the server instance and configuration from the local cache.
     *
     * @param  string  $name
     * @return array
     */
    protected function get($name)
    {
        if (!isset($this->servers[$name])) {
            $this->instanciate($name);
        }

        return $this->servers[$name];
    }

    /**
     * Instanciate the given server.
     *
     * @param  string  $name
     * @return void
     */
    protected function instanciate($name)
    {
        $config = $this->getConfig($name);

        $instance = ServerFactory::create(
            $config +
            [
                'response' => new LaravelResponseFactory($this->app['request'])
            ]
        );

        $this->servers[$name] = [
            'config' => $config,
            'instance' => $instance
        ];
    }

    /**
     * Get the filesystem connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["glide.servers.{$name}"];
    }

    /**
     * Get the default server name.
     *
     * @return string
     */
    public function getDefaultServer()
    {
        return $this->app['config']['glide.default'];
    }
}
