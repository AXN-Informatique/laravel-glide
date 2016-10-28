<?php

namespace Axn\LaravelGlide;

use Illuminate\Contracts\Foundation\Application;

class ServerManager
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The array of instanciated Glide servers.
     *
     * @var array
     */
    protected $servers = [];

    /**
     * Create a new server manager instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get a server instance.
     *
     * @param  string|null  $name
     * @return GlideServer
     */
    public function server($name = null)
    {
        if (empty($name)) {
            $name = $this->getDefaultServerName();
        }

        if (!isset($this->servers[$name])) {
            $this->servers[$name] = $this->makeServer($name);
        }

        return $this->servers[$name];
    }

    /**
     * Make the server instance.
     *
     * @param  string  $name
     * @return GlideServer
     */
    protected function makeServer($name)
    {
        $config = $this->getConfig($name);

        if (empty($config)) {
            throw new \InvalidArgumentException("Unable to instantiate Glide server because you provide en empty configuration, \"{$name}\" is probably a wrong server name.");
        }

        if (array_key_exists($config['source'], $this->app['config']['filesystems']['disks'])) {
            $config['source'] =  $this->app['filesystem']->disk($config['source'])->getDriver();
        }

        if (array_key_exists($config['cache'], $this->app['config']['filesystems']['disks'])) {
            $config['cache'] =  $this->app['filesystem']->disk($config['cache'])->getDriver();
        }

        if (isset($config['watermarks']) && array_key_exists($config['watermarks'], $this->app['config']['filesystems']['disks'])) {
            $config['watermarks'] =  $this->app['filesystem']->disk($config['watermarks'])->getDriver();
        }

        return new GlideServer($this->app, $config);
    }

    /**
     * Get the server configuration.
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
    public function getDefaultServerName()
    {
        return $this->app['config']['glide.default'];
    }

    /**
     * Dynamically pass methods to the default server.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->server(), $method], $parameters);
    }
}
