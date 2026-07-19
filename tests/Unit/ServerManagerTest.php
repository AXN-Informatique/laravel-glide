<?php

namespace Axn\LaravelGlide\Tests\Unit;

use Axn\LaravelGlide\GlideServer;
use Axn\LaravelGlide\ServerManager;
use Axn\LaravelGlide\Tests\TestCase;
use InvalidArgumentException;
use League\Flysystem\FilesystemOperator;

class ServerManagerTest extends TestCase
{
    private function manager(): ServerManager
    {
        return $this->app->make('glide');
    }

    public function test_resolves_the_default_server_when_no_name_given(): void
    {
        $server = $this->manager()->server();

        $this->assertInstanceOf(GlideServer::class, $server);
        $this->assertSame('/image', $server->getConfig()['base_url']);
        $this->assertFalse($server->getConfig()['signatures']);
    }

    public function test_resolves_a_named_server(): void
    {
        $server = $this->manager()->server('signed');

        $this->assertTrue($server->getConfig()['signatures']);
        $this->assertSame(self::SIGN_KEY, $server->getConfig()['sign_key']);
    }

    public function test_returns_the_same_instance_on_subsequent_calls(): void
    {
        $manager = $this->manager();

        $this->assertSame($manager->server('images'), $manager->server('images'));
    }

    public function test_resolves_laravel_disks_to_flysystem_operators(): void
    {
        $config = $this->manager()->server('images')->getConfig();

        $this->assertInstanceOf(FilesystemOperator::class, $config['source']);
        $this->assertInstanceOf(FilesystemOperator::class, $config['cache']);
    }

    public function test_throws_for_an_unknown_server_name(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->manager()->server('nope');
    }

    public function test_forwards_calls_to_the_default_server(): void
    {
        $url = $this->manager()->url('test.png', ['w' => 50]);

        $this->assertSame('/image/test.png?w=50', $url);
    }
}
