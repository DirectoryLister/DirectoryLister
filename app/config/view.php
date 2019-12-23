<?php

use App\Support\Helpers;

return [
    /**
     * Enable dark mode?
     *
     * Default value: true
     */
    'dark_mode' => Helpers::env('DARK_MODE', false),

    /**
     * Path to the view cache directory.
     *
     * Default value: 'app/cache/views'
     */
    'cache' => Helpers::env('VIEW_CACHE', 'app/cache/views'),
];
