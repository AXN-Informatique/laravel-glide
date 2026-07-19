<?php

namespace Axn\LaravelGlide\Tests\Unit;

use Axn\LaravelGlide\Responses\LaravelResponseFactory;
use Axn\LaravelGlide\Tests\TestCase;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaravelResponseFactoryTest extends TestCase
{
    private function cacheFilesystemWithImage(): Filesystem
    {
        $this->createSourceImage('cached.png');

        // Use the source dir as a stand-in cache filesystem: only reading matters here.
        return new Filesystem(new LocalFilesystemAdapter($this->glidePath('source')));
    }

    public function test_creates_a_streamed_response_with_cache_headers(): void
    {
        $cache = $this->cacheFilesystemWithImage();

        $response = (new LaravelResponseFactory())->create($cache, 'cached.png');

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('image/png', $response->headers->get('Content-Type'));
        $this->assertSame((string) $cache->fileSize('cached.png'), $response->headers->get('Content-Length'));
        $this->assertTrue($response->headers->getCacheControlDirective('public'));
        $this->assertSame('31536000', $response->headers->getCacheControlDirective('max-age'));
        $this->assertNotNull($response->getExpires());
    }

    public function test_returns_304_when_request_is_not_modified(): void
    {
        $cache = $this->cacheFilesystemWithImage();

        $request = Request::create('/image/cached.png');
        $request->headers->set('If-Modified-Since', gmdate('D, d M Y H:i:s', $cache->lastModified('cached.png')).' GMT');

        $response = (new LaravelResponseFactory($request))->create($cache, 'cached.png');

        $this->assertSame(304, $response->getStatusCode());
    }

    public function test_streams_the_cached_file_content(): void
    {
        $cache = $this->cacheFilesystemWithImage();

        $response = (new LaravelResponseFactory())->create($cache, 'cached.png');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertSame($cache->read('cached.png'), $content);
    }
}
