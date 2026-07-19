<?php

declare(strict_types=1);

namespace Axn\LaravelGlide\Tests\Feature;

use Axn\LaravelGlide\Facades\Glide;
use Axn\LaravelGlide\Tests\TestCase;
use Illuminate\Support\Facades\File;

class GlideClearTest extends TestCase
{
    private function generateCachedImage(): void
    {
        Glide::imageAsBase64($this->createSourceImage(), ['w' => 20]);

        $this->assertDirectoryExists($this->glidePath('cache').'/cache');
    }

    public function test_it_clears_the_cache_of_a_given_server(): void
    {
        $this->generateCachedImage();

        $this->artisan('glide:clear', ['server' => 'images'])->assertSuccessful();

        $this->assertDirectoryDoesNotExist($this->glidePath('cache').'/cache');
    }

    public function test_it_clears_all_servers_when_no_name_is_given(): void
    {
        $this->generateCachedImage();

        $this->artisan('glide:clear')->assertSuccessful();

        $this->assertDirectoryDoesNotExist($this->glidePath('cache').'/cache');
    }

    public function test_it_fails_for_an_unknown_server(): void
    {
        $this->artisan('glide:clear', ['server' => 'nope'])->assertFailed();
    }

    public function test_it_refuses_to_clear_a_server_without_cache_path_prefix(): void
    {
        // Create a cache file by hand: using the facade would freeze the server
        // config in the ServerManager before the override below.
        File::ensureDirectoryExists($this->glidePath('cache').'/cache');
        File::put($this->glidePath('cache').'/cache/dummy.png', 'cached');

        config()->set('glide.servers.images.cache_path_prefix', '');

        $this->artisan('glide:clear', ['server' => 'images'])->assertFailed();

        $this->assertDirectoryExists($this->glidePath('cache').'/cache');
    }
}
