<?php

namespace Axn\LaravelGlide\Tests\Feature;

use Axn\LaravelGlide\Facades\Glide;
use Axn\LaravelGlide\Tests\TestCase;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageGenerationTest extends TestCase
{
    public function test_image_response_generates_and_caches_a_resized_image(): void
    {
        $name = $this->createSourceImage();

        $response = Glide::imageResponse($name, ['w' => 50]);

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());

        ob_start();
        $response->sendContent();
        $content = (string) ob_get_clean();

        [$width] = getimagesizefromstring($content);
        $this->assertSame(50, $width);

        // A cache file must have been written under the cache path prefix.
        $this->assertNotEmpty(glob($this->glidePath('cache').'/cache/*'));
    }

    public function test_image_as_base64_returns_a_data_url(): void
    {
        $name = $this->createSourceImage();

        $base64 = Glide::imageAsBase64($name, ['w' => 20]);

        $this->assertStringStartsWith('data:image/png;base64,', $base64);
    }

    public function test_a_preset_is_applied(): void
    {
        $name = $this->createSourceImage();

        $base64 = Glide::imageAsBase64($name, ['p' => 'small']);

        $content = (string) base64_decode(explode(',', $base64, 2)[1], true);
        [$width, $height] = getimagesizefromstring($content);

        $this->assertSame(10, $width);
        $this->assertSame(10, $height);
    }

    public function test_an_invalid_output_format_throws(): void
    {
        $name = $this->createSourceImage();

        $this->expectException(InvalidArgumentException::class);

        Glide::imageAsBase64($name, ['fm' => 'nope']);
    }
}
