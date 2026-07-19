<?php

declare(strict_types=1);

namespace Axn\LaravelGlide\Console\Commands;

use Axn\LaravelGlide\ServerManager;
use Illuminate\Console\Command;
use InvalidArgumentException;

class GlideClear extends Command
{
    protected $signature = 'glide:clear {server? : The server name (clears all servers if omitted)}';

    protected $description = 'Clear the Glide images cache.';

    public function handle(ServerManager $glide): int
    {
        $servers = $this->argument('server') !== null
            ? [$this->argument('server')]
            : array_keys($this->laravel->make('config')->array('glide.servers'));

        foreach ($servers as $name) {
            try {
                $server = $glide->server($name)->getLeagueGlideServer();
            } catch (InvalidArgumentException $invalidArgumentException) {
                $this->components->error($invalidArgumentException->getMessage());

                return self::FAILURE;
            }

            $cachePathPrefix = $server->getCachePathPrefix();

            if ($cachePathPrefix === '') {
                $this->components->error(\sprintf('Refusing to clear the cache of server "%s": no "cache_path_prefix" configured, the whole cache disk would be deleted.', $name));

                return self::FAILURE;
            }

            $server->getCache()->deleteDirectory($cachePathPrefix);

            $this->components->info(\sprintf('Glide cache cleared for server "%s".', $name));
        }

        return self::SUCCESS;
    }
}
