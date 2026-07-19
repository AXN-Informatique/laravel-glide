<?php

return [

    // Source filesystem
    // can be the name of a disk in the filesystem
    // or the name of a driver supported by the filesystem
    'source' => 'local',

    // Source filesystem path prefix
    'source_path_prefix' => '/images',

    // Cache filesystem
    // can be the name of a disk in the filesystem
    // or the name of a driver supported by the filesystem
    'cache' => 'local',

    // Cache filesystem path prefix
    'cache_path_prefix' => '/images/.cache',

    // Group cached images into subfolders per source image
    'group_cache_in_folders' => true,

    // Append the file extension to cached image paths
    'cache_with_file_extensions' => false,

    // Temporary directory used when reading EXIF data (null: system temp dir)
    'temp_dir' => null,

    // Watermarks filesystem
    // can be the name of a disk in the filesystem
    // or the name of a driver supported by the filesystem
    'watermarks' => 'local',

    // Watermarks filesystem path prefix
    'watermarks_path_prefix' => '/images/watermarks',

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
    // see https://glide.thephpleague.com/4.0/config/defaults-and-presets/
    'defaults' => [
        'mark' => 'logo.png',
        'markw' => '30w',
        'markpad' => '5w',
    ],

    // Preset image manipulations
    // see https://glide.thephpleague.com/4.0/config/defaults-and-presets/
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
