Laravel Glide
=============

This package provides a Service Provider that allows you to very easily integrate [Glide](https://glide.thephpleague.com/) into a Laravel project with **multiple server** support and signed URLs.

Installation
------------

```sh
composer require axn/laravel-glide
```

Then publish the configuration files and generate the signing key:

```sh
php artisan vendor:publish --tag="glide-config"
php artisan glide:key-generate
```

Documentation
-------------

Full documentation is available in the [`docs/`](docs/) directory.

Upgrade
-------

See [`UPGRADE.md`](UPGRADE.md) for upgrade instructions.

License
-------

MIT
