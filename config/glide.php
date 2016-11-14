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
    | Configurations Servers
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many server configuration as you wish.
    |
    */

    'servers' => [

        /*
         * Images server's configuration.
         */
        'images' => [

            # Source filesystem
            'source' => 'local',

            # Source filesystem path prefix
            'source_path_prefix' => '/images',

            # Cache filesystem
            'cache' => 'local',

            # Cache filesystem path prefix
            'cache_path_prefix' => '/images/cache',

            # Watermarks filesystem
            'watermarks' => 'local',

            # Watermarks filesystem path prefix
            'watermarks_path_prefix' => '/watermarks',

            # Image driver (gd or imagick)
            'driver' => env('GLIDE_IMAGE_DRIVER', 'gd'),

            # Image size limit
            'max_image_size' => 2000*2000,

            # Secure your Glide image server with HTTP signatures.
            'signatures' => true,

            # Sign Key - A 128 character (or larger) signing key is recommended.
            'sign_key' => env('GLIDE_SIGN_KEY'),

            # Base URL of the images
            'base_url' => '/img',

            # Default image manipulations
            # see http://glide.thephpleague.com/1.0/config/defaults-and-presets/
            'defaults' => [
                // Examples:
                // 'mark' => 'logo.png',
                // 'markw' => '30w',
                // 'markpad' => '5w',
            ],

            # Preset image manipulations
            # see http://glide.thephpleague.com/1.0/config/defaults-and-presets/
            'presets' => [
                // Examples:
                // 'small' => [
                //     'w' => 200,
                //     'h' => 200,
                //     'fit' => 'crop',
                // ],
                // 'medium' => [
                //     'w' => 600,
                //     'h' => 400,
                //     'fit' => 'crop',
                // ]
            ]
        ],

        /*
         * Avatars server's configuration.
         */
        'avatars' => [

            'source' => 'local',

            'source_path_prefix' => '/avatars',

            'cache' => 'local',

            'cache_path_prefix' => '/avatars/cache',

            'driver' => env('GLIDE_IMAGE_DRIVER', 'gd'),

            'max_image_size' => 2000*2000,

            'signatures' => true,

            'sign_key' => env('GLIDE_SIGN_KEY'),

            'base_url' => '/avatars',
        ],
    ]
];
