Upgrade
=======

From version 1.x to version 2.x
-------------------------------

Since the third $skipValidation parameter of the `imageResponse()`, `imageAsBase64()` and `outputImage()` methods has been removed; you should look for calls to these methods if you've ever used it.

Indeed, the validation is now only driven by the configuration of the servers and only called by the `imageResponse()` method.
