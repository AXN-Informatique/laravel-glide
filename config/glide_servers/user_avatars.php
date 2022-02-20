<?php

return [
    'source' => 'avatars',

    'source_path_prefix' => '/',

    'cache' => 'avatars',

    'cache_path_prefix' => '/cache',

    'driver' => env('GLIDE_IMAGE_DRIVER', 'gd'),

    'max_image_size' => 600*600,

    'signatures' => true,

    'sign_key' => env('GLIDE_SIGN_KEY'),

    'base_url' => '/users/avatar',
];
