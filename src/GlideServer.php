<?php

namespace Axn\LaravelGlide;

use Axn\LaravelGlide\Responses\LaravelResponseFactory;
use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use League\Glide\Server;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Urls\UrlBuilderFactory;

class GlideServer
{
    /**
     * The league glide server instance.
     */
    protected ?Server $server = null;

    /**
     * Create a new GlideServer instance.
     */
    public function __construct(
        /**
         * The application instance.
         */
        protected Application $app,
        /**
         * Server configuration.
         */
        protected array $config,
    ) {}

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
        if (! $this->server instanceof Server) {
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
     */
    public function imageAsBase64(string $path, array $params = []): string
    {
        return $this->getLeagueGlideServer()->getImageAsBase64($path, $params);
    }

    /**
     * Generate and output image.
     */
    public function outputImage(string $path, array $params = []): void
    {
        $this->getLeagueGlideServer()->outputImage($path, $params);
    }

    /**
     * Validate a request signature.
     *
     * @throws SignatureException
     */
    public function validateRequest(string $path, array $params = []): void
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
