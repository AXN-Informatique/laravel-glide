<?php

namespace Axn\LaravelGlide;

use League\Glide\Server;
use InvalidArgumentException;
use League\Glide\Signatures\SignatureException;
use Axn\LaravelGlide\Responses\LaravelResponseFactory;
use Illuminate\Contracts\Foundation\Application;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Urls\UrlBuilderFactory;

class GlideServer
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Server configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The league glide server instance.
     *
     * @var Server
     */
    protected $server;

    /**
     * Create a new GlideServer instance.
     */
    public function __construct(Application $app, array $config)
    {
        $this->app = $app;

        $this->config = $config;
    }

    /**
     * Return the configuration for this glide server.
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Return the league glide server instance.
     */
    public function getLeagueGlideServer(): Server
    {
        if ($this->server === null) {
            $config = $this->config;
            $config['response'] = new LaravelResponseFactory($this->app['request']);

            $this->server = ServerFactory::create($config);
        }

        return $this->server;
    }

    /**
     * Generate and return image response.
     *
     * @return mixed Image response.
     *
     * @throws InvalidArgumentException
     */
    public function imageResponse(string $path, array $params = [])
    {
        $this->validateRequest($path, $params);

        return $this->getLeagueGlideServer()->getImageResponse($path, $params);
    }

    /**
     * Generate and return Base64 encoded image.
     *
     * @return string
     */
    public function imageAsBase64(string $path, array $params = [])
    {
        return $this->getLeagueGlideServer()->getImageAsBase64($path, $params);
    }

    /**
     * Generate and output image.
     *
     * @return void
     */
    public function outputImage(string $path, array $params = [])
    {
        $this->getLeagueGlideServer()->outputImage($path, $params);
    }

    /**
     * Validate a request signature.
     *
     * @param  string  $path
     *
     * @throws SignatureException
     */
    public function validateRequest($path, array $params = []): void
    {
        if (! $this->config['signatures']) {
            return;
        }

        $path = $this->config['base_url'].'/'.trim($path, '/');

        SignatureFactory::create($this->config['sign_key'])->validateRequest($path, $params);
    }

    /**
     * Return image url.
     */
    public function url(string $path, array $params = []): string
    {
        $urlBuilder = UrlBuilderFactory::create($this->config['base_url'], $this->config['sign_key']);

        return $urlBuilder->getUrl($path, $params);
    }

    /**
     * Dynamically pass methods to the League Glide server.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return \call_user_func_array([$this->getLeagueGlideServer(), $method], $parameters);
    }
}
