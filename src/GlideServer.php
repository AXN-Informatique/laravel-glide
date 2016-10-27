<?php

namespace Axn\LaravelGlide;

use League\Glide\Signatures\SignatureFactory;
use League\Glide\Urls\UrlBuilderFactory;
use League\Glide\Server as LeagueGlideServer;

class GlideServer
{
    /**
     * Server configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The server instance.
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
    public function __construct($config, LeagueGlideServer $server)
    {
        $this->config = $config;

        $this->server = $server;
    }

    /**
     * Generate and return image response.
     *
     * @param  string  $path
     * @param  array   $params
     * @param  bool    $skipValidation
     * @return mixed
     */
    public function imageResponse($path, array $params = [], $skipValidation = false)
    {
        $this->validateRequest($path, $params, $skipValidation);

        return $this->server->getImageResponse($path, $params);
    }

    /**
     * Generate and return Base64 encoded image.
     *
     * @param  string  $path
     * @param  array   $params
     * @param  bool    $skipValidation
     * @return string
     */
    public function imageAsBase64($path, array $params = [], $skipValidation = false)
    {
        $this->validateRequest($path, $params, $skipValidation);

        return $this->server->getImageAsBase64($path, $params);
    }

    /**
     * Generate and output image.
     *
     * @param  string  $path
     * @param  array   $params
     * @param  bool    $skipValidation
     * @return void
     */
    public function outputImage($path, array $params = [], $skipValidation = false)
    {
        $this->validateRequest($path, $params, $skipValidation);

        $this->server->outputImage($path, $params);
    }

    /**
     * Validate a request signature.
     *
     * @param  string  $path
     * @param  array   $params
     * @param  bool    $skipValidation
     * @return void
     */
    public function validateRequest($path, array $params = [], $skipValidation = false)
    {
        if (!$this->config['signatures'] || $skipValidation) {
            return;
        }

        $path = $this->config['base_url'].'/'.trim($path, '/');

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
        return call_user_func_array([$this->server, $method], $parameters);
    }
}
