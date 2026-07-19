<?php

declare(strict_types=1);

namespace Axn\LaravelGlide;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;

class ServerManager
{
    /**
     * The array of instanciated Glide servers
     *
     * @var array<string, GlideServer>
     */
    protected array $servers = [];

    /**
     * Create a new server manager instance
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
        if (\in_array($name, [null, '', '0'], true)) {
            $name = $this->app->make('config')->string('glide.default');
        }

        if (! isset($this->servers[$name])) {
            $this->servers[$name] = $this->makeServer($name);
        }

        return $this->servers[$name];
    }

    /**
     * Dynamically pass methods to the server
     *
     * @param  array<int, mixed>  $parameters
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->server()->{$method}(...$parameters);
    }

    /**
     * Make a new server instance
     */
    protected function makeServer(string $name): GlideServer
    {
        $config = $this->app->make('config')->array('glide.servers.'.$name, []);

        if ($config === []) {
            throw new InvalidArgumentException(\sprintf('Unable to instantiate Glide server because you provide an empty configuration, "%s" is probably a wrong server name.', $name));
        }

        $disks = $this->app->make('config')->array('filesystems.disks');

        if (\array_key_exists($config['source'], $disks)) {
            $config['source'] = $this->app['filesystem']->disk($config['source'])->getDriver();
        }

        if (\array_key_exists($config['cache'], $disks)) {
            $config['cache'] = $this->app['filesystem']->disk($config['cache'])->getDriver();
        }

        if (isset($config['watermarks']) && \array_key_exists($config['watermarks'], $disks)) {
            $config['watermarks'] = $this->app['filesystem']->disk($config['watermarks'])->getDriver();
        }

        return new GlideServer($this->app, $config);
    }
}
