<?php

namespace Axn\LaravelGlide\Tests\Unit;

use Axn\LaravelGlide\GlideServer;
use Axn\LaravelGlide\Tests\TestCase;
use League\Glide\Server;
use League\Glide\Signatures\SignatureException;

class GlideServerTest extends TestCase
{
    private function server(string $name = 'images'): GlideServer
    {
        return $this->app->make('glide')->server($name);
    }

    public function test_generates_an_unsigned_url(): void
    {
        $url = $this->server()->url('test.png', ['w' => 300, 'h' => 200]);

        $this->assertSame('/image/test.png?w=300&h=200', $url);
    }

    public function test_generates_a_signed_url(): void
    {
        $url = $this->server('signed')->url('test.png', ['w' => 300]);

        $this->assertStringContainsString('s=', $url);
    }

    public function test_validate_request_accepts_a_valid_signature(): void
    {
        $url = $this->server('signed')->url('test.png', ['w' => 300]);
        parse_str((string) parse_url($url, PHP_URL_QUERY), $params);

        $this->server('signed')->validateRequest('test.png', $params);

        // No exception thrown.
        $this->assertTrue(true);
    }

    public function test_validate_request_rejects_a_tampered_signature(): void
    {
        $url = $this->server('signed')->url('test.png', ['w' => 300]);
        parse_str((string) parse_url($url, PHP_URL_QUERY), $params);
        $params['w'] = 3000;

        $this->expectException(SignatureException::class);

        $this->server('signed')->validateRequest('test.png', $params);
    }

    public function test_validate_request_is_a_no_op_when_signatures_are_disabled(): void
    {
        $this->server()->validateRequest('test.png', ['w' => 300]);

        // No exception thrown.
        $this->assertTrue(true);
    }

    public function test_exposes_the_league_glide_server(): void
    {
        $this->assertInstanceOf(Server::class, $this->server()->getLeagueGlideServer());
    }
}
