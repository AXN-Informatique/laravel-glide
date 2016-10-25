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
            'sign_key' => env('GLIDE_SIGN_KEY', 'v-LK3AJdhcfcc%jt*VC2cj%nVpu+xQKvLUB%H86kRVk_4bgG8&CWM#k*b_7MLKpmTc=4GFmKFp7=K%67je-azeC5vz+r#xT?62tT?Aw%FtQ5Q2gvnwHTwqhxUh89wFr_'),

            # Base URL of the images
            'base_url' => '',

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
        'avatar' => [

            'source' => 'local',

            'source_path_prefix' => '/avatars',

            'cache' => 'local',

            'cache_path_prefix' => '/avatars/cache',

            'driver' => env('GLIDE_IMAGE_DRIVER', 'gd'),

            'max_image_size' => 2000*2000,

            'signatures' => true,

            'sign_key' => env('GLIDE_SIGN_KEY', 'v-LK3AJdhcfcc%jt*VC2cj%nVpu+xQKvLUB%H86kRVk_4bgG8&CWM#k*b_7MLKpmTc=4GFmKFp7=K%67je-azeC5vz+r#xT?62tT?Aw%FtQ5Q2gvnwHTwqhxUh89wFr_'),
        ],
    ]
];
