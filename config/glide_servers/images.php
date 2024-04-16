<?php

return [

    // Source filesystem
    // can be the name of a disk in the filesystem
    // or the name of a driver supported by the filesystem
    'source' => 'images',

    // Source filesystem path prefix
    'source_path_prefix' => '/',

    // Cache filesystem
    // can be the name of a disk in the filesystem
    // or the name of a driver supported by the filesystem
    'cache' => 'images',

    // Cache filesystem path prefix
    'cache_path_prefix' => '/cache',

    // Watermarks filesystem
    // can be the name of a disk in the filesystem
    // or the name of a driver supported by the filesystem
    'watermarks' => 'images',

    // Watermarks filesystem path prefix
    'watermarks_path_prefix' => '/watermarks',

    // Image driver (gd or imagick)
    'driver' => env('GLIDE_IMAGE_DRIVER', 'gd'),

    // Image size limit
    'max_image_size' => 2000 * 2000,

    // Secure your Glide image server with HTTP signatures
    'signatures' => true,

    // Sign Key - A 128 character (or larger) signing key is recommended
    'sign_key' => env('GLIDE_SIGN_KEY'),

    // Base URL of the images
    'base_url' => '/image',

    // Default image manipulations
    // see https://glide.thephpleague.com/2.0/config/defaults-and-presets/
    'defaults' => [
        'mark' => 'logo.png',
        'markw' => '30w',
        'markpad' => '5w',
    ],

    // Preset image manipulations
    // see https://glide.thephpleague.com/2.0/config/defaults-and-presets/
    'presets' => [
        'small' => [
            'w' => 200,
            'h' => 200,
            'fit' => 'crop',
        ],
        'medium' => [
            'w' => 600,
            'h' => 400,
            'fit' => 'fill',
        ],
        'large' => [
            'w' => 1200,
            'h' => 800,
            'fit' => 'contain',
        ],
    ],
];
