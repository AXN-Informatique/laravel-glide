Laravel Glide
=============

This package provides a Service Provider that allows you to very easily integrate Glide into a Laravel project.

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

You can configure multiples servers.

Create a route for each server you have configured:

```
Route::get('img/{path}', [
    'as' => 'image',
    'uses' => 'ImagesController@index'
])->where('path', '(.*)');
```

Create corresponding controllers:

```
<?php

namespace App\Http\Controllers;

use Glide;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function index($path, Request $request)
    {
        return Glide::imageResponse($path, $request->all());
    }
}
```

Add images to your views:

```
<img src="{{ Glide::route('image', 'example.jpg', ['w' => 300, 'fit' => 'drop']) }}" class="img-thumbnail img-responsive">
```
