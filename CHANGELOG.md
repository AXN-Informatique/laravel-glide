Changelog
=========

3.0.0 (2025-03-20)
------------------

- Minimum PHP version increased to 8.4
- Minimum Laravel version increased to 12
- Minimum Rector version increased to 2
- Changed the location and name of the "facade"


2.2.1 (2024-04-17)
------------------

- Fix "Typed property Axn\LaravelGlide\GlideServer::$server must not be accessed before initialization"

2.2.0 (2024-04-16)
------------------

- Added support for Laravel 11
- Added tools and files for code quality

2.1.0 (2023-02-20)
------------------

- Added support for Laravel 10

2.0.1 (2022-02-20)
------------------

- Enhance readme.md file
- Add an upgrade.md file
- Complete type hinting

2.0.0 (2022-02-11)
-------------------

- Added support for Laravel 9
- Removed support for Laravel <9
- Replaced dependency league/laravel-glide v1 by league/symfony-glide v2
- Removed third parameter $skipValidation in methods `imageResponse()`, `imageAsBase64()` and `outputImage()`
- Removed call to validateRequest() in methods `imageAsBase64()` and `outputImage()`

1.10.0 (2020-09-24)
-------------------

- Add support for Laravel 8

1.9.0 (2020-03-05)
------------------

- Add support for Laravel 7

1.8.0 (2019-12-29)
------------------

- Add support for Laravel 6

1.7.0 (2019-03-07)
------------------

- Add support for Laravel 5.8

1.6.0 (2018-09-11)
------------------

- add support for Laravel 5.7

1.5.0 (2018-06-26)
------------------

- add support for Laravel 5.6

1.4.0 (2017-05-15)
------------------

- add support for Laravel 5.5

1.3.0 (2017-05-15)
------------------

- add support for Laravel 5.4
- change composer constraints

1.2.0 (2016-11-14)
------------------

- add console command to generate GLIDE_SIGN_KEY
- remove default value for GLIDE_SIGN_KEY from config file

1.1.1 (2016-10-31)
------------------

- move to Github
- add MIT licence file

1.1.0 (2016-10-28)
------------------

- Lazy load League Glide Server
- Fix watermark source handling
- Fix base_url examples
- update Laravel compatibilities

1.0.0 (2016-10-27)
------------------

- First release.
