<?php

use App\Support\Helpers;
use Psr\Container\ContainerInterface;

return [
    /**
     * Enable application debugging and display error messages.
     *
     * !!! WARNING !!!
     * It is recommended that debug remains OFF unless troubleshooting an issue.
     * Leaving this enabled WILL cause leakage of sensitive server information.
     *
     * Default value: false
     */
    'debug' => Helpers::env('APP_DEBUG', false),

    /**
     * The application interface language.
     *
     * Possible values: See 'app/translations' folder for available translations.
     *
     * Defualt value: en
     */
    'language' => Helpers::env('APP_LANGUAGE', 'en'),

    /**
     * Enable dark mode?
     *
     * Default value: false
     */
    'dark_mode' => Helpers::env('DARK_MODE', false),

    /**
     * Parse and render README files on the page.
     *
     * Default value: true
     */
    'display_readmes' => Helpers::env('DISPLAY_READMES', true),

    /**
     * Enable downloading of directories as a zip archive.
     *
     * Default value: true
     */
    'zip_downloads' => Helpers::env('ZIP_DOWNLOADS', true),

    /**
     * Your Google analytics tracking ID.
     *
     * Expected format: 'UA-123456789-0'
     * Default value: false
     */
    'google_analytics_id' => Helpers::env('GOOGLE_ANALYTICS_ID', false),

    /**
     * Sorting order of files and folders.
     *
     * Possible values: type, natural, name, accessed, changed, modified
     * Default value: type
     */
    'sort_order' => Helpers::env('SORT_ORDER', 'type'),

    /**
     * When enabled, reverses the order of files (after sorting is applied).
     *
     * Default value: false
     */
    'reverse_sort' => Helpers::env('REVERSE_SORT', false),

    /**
     * File used by 'hidden_files' to define the list of hidden files.
     *
     * Default value: '.hidden'
     */
    'hidden_files_list' => Helpers::env('HIDDEN_FILES_LIST', '.hidden'),

    /**
     * Array of files that will be hidden from the listing. Supports glob
     * patterns (e.g. *.txt, file.ya?ml, etc.).
     *
     * By defualt this will look for a '.hidden' file in the app root directory.
     * If found, each line of this file will be used as an ignore pattern.
     *
     * Default value: Array loaded from '.hidden' file if present, otherwise
     *                an empty array ([])
     */
    'hidden_files' => static function (ContainerInterface $container): array {
        if (! is_readable($container->get('hidden_files_list'))) {
            return [];
        }

        return file(
            $container->get('hidden_files_list'),
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );
    },

    /**
     * Whether or not to hide application files/directories form the listing.
     *
     * Default value: true
     */
    'hide_app_files' => Helpers::env('HIDE_APP_FILES', true),

    /**
     * Hide the files Version Control System (i.e. Git and Mercurial) use to
     * store their metadata.
     *
     * Default value: true
     */
    'hide_vcs_files' => Helpers::env('HIDE_VSC_FILES', true),

    /**
     * Default date format. For additional info on date formatting see:
     * https://www.php.net/manual/en/function.date.php.
     *
     * Default value: 'Y-m-d H:i:s'
     */
    'date_format' => Helpers::env('DATE_FORMAT', 'Y-m-d H:i:s'),

    /**
     * Timezone used for date formatting. For a list of supported timezones see:
     * https://www.php.net/manual/en/timezones.php.
     *
     * Default value: The server's timezone
     */
    'timezone' => Helpers::env('TIMEZONE', date_default_timezone_get()),

    /**
     * The maximum file size (in bytes) that can be hashed. This helps to
     * prevent timeouts for excessively large files.
     *
     * Default value: 1000000000
     */
    'max_hash_size' => Helpers::env('MAX_HASH_SIZE', 1000000000),

    /**
     * Path to the view cache directory.
     * Set to 'false' to disable view caching entirely.
     *
     * Default value: 'app/cache/views'
     */
    'view_cache' => Helpers::env('VIEW_CACHE', 'app/cache/views'),

    /**
     * HTTP expires values.
     *
     * Possible values: An array of mime types mapped to their cache duration
     * as a relative datetime string.
     *
     * Default value: [
     *     'application/zip' => '+1 hour',
     *     'text/json' => '+1 hour',
     * ]
     */
    'http_expires' => [
        'application/zip' => '+1 hour',
        'text/json' => '+1 hour',
    ],

    /**
     * Array of icon definitions where the array key is the file extension
     * (without a preceding dot) and the array value is the desired Font Awesome
     * class names.
     *
     * Default value: Array loaded from 'icons.php' config file
     */
    'icons' => static function (ContainerInterface $container): array {
        return require $container->get('icons_config');
    },
];
