<?php

declare(strict_types=1);

namespace Axn\LaravelGlide\Tests\Feature;

use Axn\LaravelGlide\Facades\Glide;
use Axn\LaravelGlide\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class RoutesTest extends TestCase
{
    public function test_it_registers_a_named_route_per_server(): void
    {
        Glide::routes();

        $this->assertTrue(Route::has('glide.images'));
        $this->assertTrue(Route::has('glide.signed'));
    }

    public function test_it_registers_only_the_given_servers(): void
    {
        Glide::routes(['images']);

        $this->assertTrue(Route::has('glide.images'));
        $this->assertFalse(Route::has('glide.signed'));
    }

    public function test_it_serves_a_resized_image(): void
    {
        Glide::routes();

        $name = $this->createSourceImage();

        $response = $this->get('/image/'.$name.'?w=50');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');

        [$width] = getimagesizefromstring($response->streamedContent());
        $this->assertSame(50, $width);
    }

    public function test_it_returns_404_for_a_missing_source_image(): void
    {
        Glide::routes();

        $this->get('/image/missing.png?w=50')->assertNotFound();
    }

    public function test_it_serves_an_image_with_a_valid_signature(): void
    {
        Glide::routes(['signed']);

        $name = $this->createSourceImage();
        $url = Glide::server('signed')->url($name, ['w' => 30]);

        $this->get($url)->assertOk();
    }

    public function test_it_returns_404_for_a_tampered_signature(): void
    {
        Glide::routes(['signed']);

        $name = $this->createSourceImage();
        $url = Glide::server('signed')->url($name, ['w' => 30]);

        $this->get(str_replace('w=30', 'w=3000', $url))->assertNotFound();
    }
}
