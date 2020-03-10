<?php

use App\Support\Helpers;

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
    'debug' => Helpers::env('APP_DEBUG'),

    /**
     * The application interface language.
     *
     * Possible values: See 'app/translations' folder for available translations.
     *
     * Defualt value: en
     */
    'language' => Helpers::env('APP_LANGUAGE'),

    /**
     * Enable dark mode?
     *
     * Default value: false
     */
    'dark_mode' => Helpers::env('DARK_MODE'),

    /**
     * Parse and render README files on the page.
     *
     * Default value: true
     */
    'display_readmes' => Helpers::env('DISPLAY_READMES'),

    /**
     * Enable downloading of directories as a zip archive.
     *
     * Default value: true
     */
    'zip_downloads' => Helpers::env('ZIP_DOWNLOADS'),

    /**
     * Your Google analytics tracking ID.
     *
     * Expected format: 'UA-123456789-0'
     * Default value: false
     */
    'google_analytics_id' => Helpers::env('GOOGLE_ANALYTICS_ID'),

    /**
     * Sorting order of files and folders.
     *
     * Possible values: type, natural, name, accessed, changed, modified
     * Default value: type
     */
    'sort_order' => Helpers::env('SORT_ORDER'),

    /**
     * When enabled, reverses the order of files (after sorting is applied).
     *
     * Default value: false
     */
    'reverse_sort' => Helpers::env('REVERSE_SORT'),

    /**
     * Array of files that will be hidden from the listing.
     * Supports glob patterns.
     *
     * Default value: []
     */
    'hidden_files' => [
        // ...
    ],

    /**
     * Whether or not to hide application files/directories form the listing.
     *
     * Default value: true
     */
    'hide_app_files' => Helpers::env('HIDE_APP_FILES'),

    /**
     * Hide the files Version Control System (i.e. Git and Mercurial) use to
     * store their metadata.
     *
     * Default value: true
     */
    'hide_vcs_files' => Helpers::env('HIDE_VSC_FILES'),

    /**
     * Default date format. For additional info on date formatting see:
     * https://www.php.net/manual/en/function.date.php.
     *
     * Default value: 'Y-m-d H:i:s'
     */
    'date_format' => Helpers::env('DATE_FORMAT'),

    /**
     * The maximum file size (in bytes) that can be hashed. This helps to
     * prevent timeouts for excessively large files.
     *
     * Default value: 1000000000
     */
    'max_hash_size' => Helpers::env('MAX_HASH_SIZE'),

    /**
     * Path to the view cache directory.
     * Set to 'false' to disable view caching entirely.
     *
     * Default value: 'app/cache/views'
     */
    'view_cache' => Helpers::env('VIEW_CACHE'),

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
];
