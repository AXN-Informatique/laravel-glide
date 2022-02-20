<?php

namespace Axn\LaravelGlide;

use Illuminate\Contracts\Foundation\Application;

class ServerManager
{
    /**
     * The application instance
     *
     * @var Application
     */
    protected $app;

    /**
     * The array of instanciated Glide servers
     *
     * @var array
     */
    protected $servers = [];

    /**
     * Create a new server manager instance
     *
     * @param Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get a server instance
     *
     * @param string|null $name
     * @return GlideServer
     */
    public function server(?string $name = null): GlideServer
    {
        if (empty($name)) {
            $name = $this->app['config']['glide']['default'];
        }

        if (! isset($this->servers[$name])) {
            $this->servers[$name] = $this->makeServer($name);
        }

        return $this->servers[$name];
    }

    /**
     * Dynamically pass methods to the server
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters): mixed
    {
        return call_user_func_array([$this->server(), $method], $parameters);
    }

    /**
     * Make the a new server instance
     *
     * @param string $name
     * @return GlideServer
     */
    protected function makeServer(string $name): GlideServer
    {
        $config = $this->app['config']['glide']['servers'][$name];

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
}
