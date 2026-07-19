<?php

return [
    'source' => 'local',

    'source_path_prefix' => '/users/avatars',

    'cache' => 'local',

    'cache_path_prefix' => '/users/avatars/.cache',

    'group_cache_in_folders' => true,

    'cache_with_file_extensions' => false,

    'temp_dir' => null,

    'driver' => env('GLIDE_IMAGE_DRIVER', 'gd'),

    'max_image_size' => 600 * 600,

    'signatures' => true,

    'sign_key' => env('GLIDE_SIGN_KEY'),

    'base_url' => '/users/avatar',
];
