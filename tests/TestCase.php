<?php

namespace Axn\LaravelGlide\Tests;

use Axn\LaravelGlide\ServiceProvider;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public const string SIGN_KEY = 'testing-sign-key-testing-sign-key-testing-sign-key';

    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('filesystems.disks.glide-source', [
            'driver' => 'local',
            'root' => $this->glidePath('source'),
        ]);

        $app['config']->set('filesystems.disks.glide-cache', [
            'driver' => 'local',
            'root' => $this->glidePath('cache'),
        ]);

        $app['config']->set('glide.default', 'images');

        $app['config']->set('glide.servers.images', [
            'source' => 'glide-source',
            'source_path_prefix' => '/',
            'cache' => 'glide-cache',
            'cache_path_prefix' => '/cache',
            'driver' => 'gd',
            'max_image_size' => 2000 * 2000,
            'signatures' => false,
            'sign_key' => null,
            'base_url' => '/image',
            'defaults' => [],
            'presets' => [
                'small' => ['w' => 10, 'h' => 10, 'fit' => 'crop'],
            ],
        ]);

        $app['config']->set('glide.servers.signed', [
            'source' => 'glide-source',
            'source_path_prefix' => '/',
            'cache' => 'glide-cache',
            'cache_path_prefix' => '/cache',
            'driver' => 'gd',
            'max_image_size' => 2000 * 2000,
            'signatures' => true,
            'sign_key' => self::SIGN_KEY,
            'base_url' => '/signed-image',
            'defaults' => [],
            'presets' => [],
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        File::ensureDirectoryExists($this->glidePath('source'));
        File::ensureDirectoryExists($this->glidePath('cache'));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->glidePath());

        parent::tearDown();
    }

    protected function glidePath(string $path = ''): string
    {
        return storage_path('framework/testing/glide'.($path !== '' ? '/'.$path : ''));
    }

    /**
     * Create a real PNG image in the source disk and return its name.
     */
    protected function createSourceImage(string $name = 'test.png', int $width = 100, int $height = 80): string
    {
        $image = imagecreatetruecolor($width, $height);
        imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, (int) imagecolorallocate($image, 200, 50, 50));
        imagepng($image, $this->glidePath('source').'/'.$name);

        return $name;
    }
}
