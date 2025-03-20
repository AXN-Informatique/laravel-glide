<?php

namespace Axn\LaravelGlide\Facades;

use Illuminate\Support\Facades\Facade;

class Glide extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'glide';
    }
}
