<?php

declare(strict_types=1);

namespace Axn\LaravelGlide\Facades;

use Axn\LaravelGlide\GlideServer;
use Axn\LaravelGlide\ServerManager;
use Illuminate\Support\Facades\Facade;
use League\Glide\Server;

/**
 * @method static GlideServer server(?string $name = null)
 * @method static string url(string $path, array $params = [])
 * @method static mixed imageResponse(string $path, array $params = [])
 * @method static string imageAsBase64(string $path, array $params = [])
 * @method static void outputImage(string $path, array $params = [])
 * @method static void validateRequest(string $path, array $params = [])
 * @method static array getConfig()
 * @method static Server getLeagueGlideServer()
 *
 * @see ServerManager
 * @see GlideServer
 */
class Glide extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'glide';
    }
}
