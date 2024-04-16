Laravel Glide
=============

This package provides a Service Provider that allows you to very easily integrate Glide into a Laravel project.
Moreover, **multiple servers** can be configured.

Glide is a easy on-demand image manipulation library written in PHP. It's part of the League of Extraordinary Packages.

Using this package you'll be able to generate image manipulations on the fly and generate URL's to those images.
These URL's will be signed so only you will be able to specify which manipulations should be generated.
Every manipulation will be cached.

Upgrade
-------

For upgrade instructions please see the `UPGRADE.md` file.


Installation
------------

Install through composer:

```sh
composer require axn/laravel-glide
```

## Environment

Edit your environment file by adding the following lines:

```
GLIDE_IMAGE_DRIVER=gd
GLIDE_SIGN_KEY=SetComplicatedSignKey
```

Obviously you have to adjust the values according to your environment.

The driver can be "gd" or "imagick".

A 128 character (or larger) signing key is recommended. To help you do this, you can run the following command:

```sh
php artisan glide:key-generate
```

## Filesytem disks

For file storage it is possible to use as many servers as desired. In order to keep an organized storage architecture, we advise you to create several disks according to their uses.

You will then create a disk in the file `/config/filesystems.php` to store our images, and another one for user avatars:

```php
'disks' => [
    //...
    'images' => [
        'driver' => 'local',
        'root' => storage_path('app/images'),
    ],

    'avatars' => [
        'driver' => 'local',
        'root' => storage_path('app/images'),
    ],
    //...
],
```

## Configuration

Then publish the configuration files using artisan:

```sh
php artisan vendor:publish --tag="glide-config"
```

These files then published are rather to be taken as an example. Take the time to read the comments to understand what you can configure.

For example in these files we have configured two servers, one for images and a second for user avatars.

The name of the filestem "source", "cache" and "watermarks" must then be the same as that of the disk in the configuration of the filesystem of the application.
The path prefixes will then depend on the configured disk.

*It's not very obvious at first glance, but it gives you a lot of freedom to organize the storage of your files.*
And when you understand how it works and how you can benefit from it, you will be happy with the flexibility it gives you.

After looking at these files, feel free to delete, modify and create your own files as needed.


Usage
-----

Create a route for each server you have configured:

```php
// App/Http/routes.php

use App\Http\Controllers\GlideController;
use Illuminate\Support\Facades\Route;

Route::get(config('glide.servers.images.base_url').'/{path}', [GlideController::class, 'images'])
    ->name('images')
    ->where('path', '(.*)');

Route::get(config('glide.servers.avatars.base_url').'/{path}', [GlideController::class, 'avatars'])
    ->name('avatars')
    ->where('path', '(.*)');
```

Create corresponding controllers/actions:

```php
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

```blade
<img src="{{ Glide::server('images')->url('example1.jpg', ['p' => 'medium]) }}">

<img src="{{ Glide::server('avatars')->url('example2.jpg', ['w' => 50, 'fit' => 'crop']) }}">
```
