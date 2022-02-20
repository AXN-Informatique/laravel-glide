<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Server Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default server configuration (see below)
    | that should be used by Laravel Glide.
    |
    */

    'default' => 'images',

    /*
    |--------------------------------------------------------------------------
    | Servers
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many server configuration as you wish.
    |
    | For each server you can define a Glide configuration.
    | see https://glide.thephpleague.com/2.0/config/setup/
    |
    | For convenience we have split into several files.
    |
    */

    'servers' => [

        /*
         * Images server's configuration
         */
        'images' => require __DIR__.'/glide_servers/images.php',

        /*
         * Avatars server's configuration.
         */
        'avatars' => require __DIR__.'/glide_servers/user_avatars.php',
    ]
];
