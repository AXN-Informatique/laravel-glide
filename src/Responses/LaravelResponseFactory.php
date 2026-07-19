<?php

declare(strict_types=1);

namespace Axn\LaravelGlide\Responses;

use League\Flysystem\FilesystemOperator;
use League\Glide\Responses\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaravelResponseFactory implements ResponseFactoryInterface
{
    /**
     * Create LaravelResponseFactory instance.
     */
    public function __construct(
        /**
         * Request object to check "is not modified".
         */
        protected ?Request $request = null,
    ) {}

    /**
     * Create the response.
     *
     * @param  FilesystemOperator  $cache  The cache file system.
     * @param  string  $path  The cached file path.
     */
    public function create(FilesystemOperator $cache, string $path): StreamedResponse
    {
        $stream = $cache->readStream($path);

        $response = new StreamedResponse();
        $response->headers->set('Content-Type', $cache->mimeType($path));
        $response->headers->set('Content-Length', (string) $cache->fileSize($path));
        $response->setPublic();
        $response->setMaxAge(31536000);
        $response->setExpires(date_create()->modify('+1 years'));

        if ($this->request instanceof Request) {
            $response->setLastModified(date_create()->setTimestamp($cache->lastModified($path)));
            $response->isNotModified($this->request);
        }

        $response->setCallback(function () use ($stream): void {
            if (ftell($stream) !== 0) {
                rewind($stream);
            }

            fpassthru($stream);
            fclose($stream);
        });

        return $response;
    }
}
