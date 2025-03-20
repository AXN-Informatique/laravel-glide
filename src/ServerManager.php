<?php

namespace Axn\LaravelGlide;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;

class ServerManager
{
    /**
     * The array of instanciated Glide servers
     *
     * @var array
     */
    protected $servers = [];

    /**
     * Create a new server manager instance
     *
     * @return void
     */
    public function __construct(
        /**
         * The application instance
         */
        protected Application $app
    ) {}

    /**
     * Get a server instance
     */
    public function server(?string $name = null): GlideServer
    {
        if ($name === null || $name === '' || $name === '0') {
            $name = $this->app['config']['glide']['default'];
        }

        if (! isset($this->servers[$name])) {
            $this->servers[$name] = $this->makeServer($name);
        }

        return $this->servers[$name];
    }

    /**
     * Dynamically pass methods to the server
     */
    public function __call(string $method, array $parameters): mixed
    {
        return \call_user_func_array([$this->server(), $method], $parameters);
    }

    /**
     * Make a new server instance
     */
    protected function makeServer(string $name): GlideServer
    {
        $config = $this->app['config']['glide']['servers'][$name];

        if (empty($config)) {
            throw new InvalidArgumentException(\sprintf('Unable to instantiate Glide server because you provide en empty configuration, "%s" is probably a wrong server name.', $name));
        }

        if (\array_key_exists($config['source'], $this->app['config']['filesystems']['disks'])) {
            $config['source'] = $this->app['filesystem']->disk($config['source'])->getDriver();
        }

        if (\array_key_exists($config['cache'], $this->app['config']['filesystems']['disks'])) {
            $config['cache'] = $this->app['filesystem']->disk($config['cache'])->getDriver();
        }

        if (isset($config['watermarks']) && \array_key_exists($config['watermarks'], $this->app['config']['filesystems']['disks'])) {
            $config['watermarks'] = $this->app['filesystem']->disk($config['watermarks'])->getDriver();
        }

        return new GlideServer($this->app, $config);
    }
}
