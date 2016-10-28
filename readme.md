Laravel Glide
=============

This package provides a Service Provider that allows you to very easily integrate Glide into a Laravel project.
Moreover, multiple servers can be configured.

Glide is a easy on-demand image manipulation library written in PHP. It's part of the League of Extraordinary Packages.

Using this package you'll be able to generate image manipulations on the fly and generate URL's to those images.
These URL's will be signed so only you will be able to specify which manipulations should be generated.
Every manipulation will be cached.

Installation
------------

Install through composer:

```
composer require axn/laravel-glide
```

Add the service provider to the array of providers in `config/app.php`:

```
// config/app.php
'provider' => [
    ...
    Axn\LaravelGlide\ServiceProvider::class,
    ...
];
```

If you intend to use facade, install those as well:

```
// config/app.php
'aliases' => [
    ...
    'Glide' => Axn\LaravelGlide\Facade::class,
    ...
];
```

Publish the config file of the package using artisan:

```
php artisan vendor:publish --provider="Axn\LaravelGlide\ServiceProvider"
```

Usage
-----

Create a route for each server you have configured:

```
Route::get(config('glide.servers.images.base_url').'/{path}', [
    'uses' => 'GlideController@images'
])->where('path', '(.*)');

Route::get(config('glide.servers.avatars.base_url').'/{path}', [
    'uses' => 'GlideController@avatars'
])->where('path', '(.*)');
```

Create corresponding controllers/actions:

```
<?php

namespace App\Http\Controllers;

use Glide;
use Illuminate\Http\Request;

class GlideController extends Controller
{
    public function images($path, Request $request)
    {
        return Glide::server('images')->imageResponse($path, $request->all());
    }

    public function avatars($path, Request $request)
    {
        return Glide::server('avatars')->imageResponse($path, $request->all());
    }
}
```

Add image to your views:

```
<!-- From default server -->
<img src="{{ Glide::url('example1.jpg', ['w' => 500, 'h' => 300, 'fit' => 'crop']) }}">

<!-- From "avatars" server -->
<img src="{{ Glide::server('avatars')->url('example2.jpg', ['w' => 250, 'fit' => 'fill']) }}">
```
