<?php

namespace Axn\LaravelGlide;

use League\Glide\Signatures\SignatureFactory;

class Glide
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The server manager instance.
     *
     * @var ServersManager
     */
    protected $serverManager;

    /**
     * The server instance.
     *
     * @var \League\Glide\Server
     */
    protected $server;

    /**
     * Server configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new Glide instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application  $app
     * @param ServersManager
     * @return void
     */
    public function __construct($app, ServersManager $serverManager)
    {
        $this->app = $app;

        $this->serverManager = $serverManager;

        $this->server = $serverManager->server();

        $this->config = $serverManager->config();
    }

    /**
     * Change server to use.
     *
     * @param string $name
     */
    public function server($name = null)
    {
        $this->server = $this->serverManager->server($name);

        $this->config = $this->serverManager->config($name);

        return $this;
    }

    /**
     * Generate and return image response.
     *
     * @param string $path
     * @param array $params
     * @return
     */
    public function imageResponse($path, array $params = [])
    {
        $this->validateRequest($path, $params);

        return $this->server->getImageResponse($path, $params);
    }

    /**
     * Generate and return Base64 encoded image.
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function imageAsBase64($path, array $params = [])
    {
        $this->validateRequest($path, $params);

        return $this->server->getImageAsBase64($path, $params);
    }

    /**
     * Generate and output image.
     *
     * @param string $path
     * @param array $params
     */
    public function outputImage($path, array $params = [])
    {
        $this->validateRequest($path, $params);

        return $this->server->outputImage($path, $params);
    }

    /**
     * Validate a request signature.
     *
     * @param  string
     * @param  array
     */
    protected function validateRequest($path, array $params = [])
    {
        if ($this->config['signatures']) {
            SignatureFactory::create($this->config['sign_key'])->validateRequest($path, $params);
        }
    }
}
