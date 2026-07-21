<?php

declare(strict_types=1);

namespace Axn\LaravelGlide\Http\Controllers;

use Axn\LaravelGlide\ServerManager;
use Illuminate\Http\Request;
use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Signatures\SignatureException;

class GlideController
{
    /**
     * Serve an image for the Glide server set as "server" route default.
     */
    public function __invoke(Request $request, ServerManager $glide, string $path): mixed
    {
        $server = (string) $request->route('server');

        try {
            return $glide->server($server)->imageResponse($path, $request->query());
        } catch (SignatureException|FileNotFoundException) {
            abort(404);
        }
    }
}
