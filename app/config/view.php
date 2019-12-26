<?php

use App\Support\Helpers;

return [
    /**
     * Enable dark mode?
     *
     * Default value: false
     */
    'dark_mode' => Helpers::env('DARK_MODE'),

    /**
     * Default date format. For additional info on date formatting see:
     * https://www.php.net/manual/en/function.date.php.
     *
     * Default value: 'Y-m-d H:i:s'
     */
    'date_format' => Helpers::env('DATE_FORMAT'),

    /**
     * Path to the view cache directory.
     * Set to 'false' to disable view caching entirely.
     *
     * Default value: 'app/cache/views'
     */
    'cache' => Helpers::env('VIEW_CACHE'),
];
