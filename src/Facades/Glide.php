<?php

namespace Axn\LaravelGlide\Facades;

use Illuminate\Support\Facades\Facade;

class Glide extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'glide';
    }
}
