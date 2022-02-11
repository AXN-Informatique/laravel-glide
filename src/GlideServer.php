<?php

namespace Axn\LaravelGlide;

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
     * @var LeagueGlideServer
     */
    protected $server;

    /**
     * Create a new GlideServer instance.
     *
     * @param  array             $config
     * @param  LeagueGlideServer $server
     * @return void
     */
    public function __construct(Application $app, array $config)
    {
        $this->app = $app;

        $this->config = $config;
    }

    /**
     * Return the configuration for this glide server.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Return the league glide server instance for this glide server.
     *
     * @return \League\Glide\Server
     */
    public function getLeagueGlideServer()
    {
        if (null === $this->server) {
            $config = $this->config + [
                'response' => new LaravelResponseFactory($this->app['request'])
            ];

            $this->server = ServerFactory::create($config);
        }

        return $this->server;
    }

    /**
     * Generate and return image response.
     *
     * @param  string  $path
     * @param  array   $params
     * @return mixed
     */
    public function imageResponse($path, array $params = [])
    {
        $this->validateRequest($path, $params);

        return $this->getLeagueGlideServer()->getImageResponse($path, $params);
    }

    /**
     * Generate and return Base64 encoded image.
     *
     * @param  string  $path
     * @param  array   $params
     * @return string
     */
    public function imageAsBase64($path, array $params = [])
    {
        return $this->getLeagueGlideServer()->getImageAsBase64($path, $params);
    }

    /**
     * Generate and output image.
     *
     * @param  string  $path
     * @param  array   $params
     * @return void
     */
    public function outputImage($path, array $params = [])
    {
        $this->getLeagueGlideServer()->outputImage($path, $params);
    }

    /**
     * Validate a request signature.
     *
     * @param  string  $path
     * @param  array   $params
     * @return void
     */
    public function validateRequest($path, array $params = [])
    {
        if (!$this->config['signatures']) {
            return;
        }

        $path = $this->config['base_url'] . '/' . trim($path, '/');

        SignatureFactory::create($this->config['sign_key'])->validateRequest($path, $params);
    }

    /**
     * Return image url.
     *
     * @param  string  $path
     * @param  array   $params
     * @return string
     */
    public function url($path, array $params = [])
    {
        $urlBuilder = UrlBuilderFactory::create($this->config['base_url'], $this->config['sign_key']);

        return $urlBuilder->getUrl($path, $params);
    }

    /**
     * Dynamically pass methods to the League Glide server.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->getLeagueGlideServer(), $method], $parameters);
    }
}
