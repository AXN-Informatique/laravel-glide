UPGRADE
=======

From version 2.x to version 3.x
-------------------------------

This package now requires at least **PHP 8.4** and **Laravel 12**.
To install this new version you must update your application accordingly.


From version 1.x to version 2.x
-------------------------------

Since the third $skipValidation parameter of the `imageResponse()`, `imageAsBase64()` and `outputImage()` methods has been removed; you should look for calls to these methods if you've ever used it.

Indeed, the validation is now only driven by the configuration of the servers and only called by the `imageResponse()` method.
