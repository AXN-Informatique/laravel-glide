UPGRADE
=======

From version 3.x to version 4.x
-------------------------------

This package now requires **Laravel 13** and relies on **Glide 4** (Intervention Image v4)
through a direct `league/glide` dependency; `league/glide-symfony` is no longer installed.

The public API of the package is unchanged (`Glide` facade, `ServerManager`, `GlideServer`,
`glide:key-generate`), but you should be aware of the following behavior changes inherited
from Glide 4 / Intervention Image v4:

- An invalid output format (e.g. `?fm=xxx`) now throws an `InvalidArgumentException`
  instead of being silently ignored.
- Watermark transparency (`markalpha`, still expressed in the 0-100 range) is now rendered
  correctly with Intervention Image v4 (normalized internally by Glide) — do NOT convert
  your values to 0-1.
- New supported formats depending on the driver: HEIC, progressive JPEG (`fm=pjpg`).
- If your application extended `Axn\LaravelGlide\Responses\LaravelResponseFactory` or
  referenced `League\Glide\Responses\SymfonyResponseFactory`, note that the factory now
  implements `League\Glide\Responses\ResponseFactoryInterface` directly.
- If your application used the Intervention Image API directly, it must be migrated to
  Intervention Image v4.

Also note: requesting an unknown Glide server name now throws an `InvalidArgumentException`
(previously an `ErrorException` "Undefined array key").

New per-server config options are available (with Glide's defaults): `group_cache_in_folders`,
`cache_with_file_extensions` and `temp_dir`. Advanced options (`cache_path_callable`,
`encoder`, array driver options) can also be passed through the server config untouched.

From version 2.x to version 3.x
-------------------------------

This package now requires at least **PHP 8.4** and **Laravel 12**.
To install this new version you must update your application accordingly.

The facade has been moved and renamed:

Before: `Axn\LaravelGlide\Facade`
Now: `Axn\LaravelGlide\Facades\Glide`

In the vast majority of cases, you should search for:

```php
use Axn\LaravelGlide\Facade as Glide;
```

Or simply:

```php
use Glide;
```

And replace with:

```php
use Axn\LaravelGlide\Facades\Glide;
```

From version 1.x to version 2.x
-------------------------------

Since the third $skipValidation parameter of the `imageResponse()`, `imageAsBase64()` and `outputImage()` methods has been removed; you should look for calls to these methods if you've ever used it.

Indeed, the validation is now only driven by the configuration of the servers and only called by the `imageResponse()` method.
