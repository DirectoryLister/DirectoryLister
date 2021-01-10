<?php

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
    'debug' => DI\env('APP_DEBUG', false),

    /**
     * Allow another server or path rule to handle files when listing an outside
     * directory.
     *
     * Default value: ''
     */
    'file_url_prefix' => DI\env('FILE_URL_PREFIX', ''),

    /**
     * The application interface language.
     *
     * Possible values: See 'app/translations' folder for available translations.
     *
     * Defualt value: 'en'
     */
    'language' => DI\env('APP_LANGUAGE', 'en'),

    /**
     * The title of your directory listing. This will be displayed in the
     * browser tab/title bar along with the current path.
     *
     * Default value: 'Directory Lister'
     */
    'site_title' => DI\env('SITE_TITLE', '• Directory Lister'),

    /**
     * Meta tag description text.
     *
     * Default value: 'Yet another directory listing, powered by Directory Lister.'.
     */
    'meta_description' => DI\env('META_DESCRIPTION', 'Yet another directory listing, powered by Directory Lister.'),

    /**
     * Text of the 'home' link in the navigation breadcrumbs. If undefined or
     * null will use the translated form of "home" from your selected language.
     *
     * Default value: null
     */
    'home_text' => DI\env('HOME_TEXT', null),

    /**
     * Parse and render README files on the page.
     *
     * Default value: true
     */
    'display_readmes' => DI\env('DISPLAY_READMES', true),

    /**
     * Show READMEs before the file listing.
     *
     * Default value: false
     */
    'readmes_first' => DI\env('READMES_FIRST', false),

    /**
     * Enable downloading of directories as a zip archive.
     *
     * Default value: true
     */
    'zip_downloads' => DI\env('ZIP_DOWNLOADS', true),

    /**
     * Your Google analytics tracking ID.
     *
     * Expected format: 'UA-123456789-0'
     * Default value: false
     */
    'google_analytics_id' => DI\env('GOOGLE_ANALYTICS_ID', false),

    /**
     * Your Matomo analytics URL.
     *
     *  Default value: false
     */
    'matomo_analytics_url' => DI\env('MATOMO_ANALYTICS_URL', false),

    /**
     * Your Matomo analytics site ID.
     *
     * Default value: false
     */
    'matomo_analytics_site_id' => DI\env('MATOMO_ANALYTICS_SITE_ID', false),

    /**
     * Sorting order of files and folders.
     *
     * Possible values: type, natural, name, accessed, changed, modified
     * Default value: 'type'
     */
    'sort_order' => DI\env('SORT_ORDER', 'type'),

    /**
     * When enabled, reverses the order of files (after sorting is applied).
     *
     * Default value: false
     */
    'reverse_sort' => DI\env('REVERSE_SORT', false),

    /**
     * File containing hidden file definitions. Will be merged with definitions
     * from the 'hidden_files' configuration option.
     *
     * Default value: '.hidden'
     */
    'hidden_files_list' => DI\env('HIDDEN_FILES_LIST', '.hidden'),

    /**
     * Array of hidden file definitions. Will be merged with definitions in the
     * file defined in the 'hidden_files_list' configuration option. Supports
     * glob patterns (e.g. *.txt, file.{yml,yaml}, etc.).
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
    'hide_app_files' => DI\env('HIDE_APP_FILES', true),

    /**
     * Hide the files Version Control System (i.e. Git and Mercurial) use to
     * store their metadata.
     *
     * Default value: true
     */
    'hide_vcs_files' => DI\env('HIDE_VSC_FILES', true),

    /**
     * Default date format. For additional info on date formatting see:
     * https://www.php.net/manual/en/function.date.php.
     *
     * Default value: 'Y-m-d H:i:s'
     */
    'date_format' => DI\env('DATE_FORMAT', 'Y-m-d H:i:s'),

    /**
     * Timezone used for date formatting. For a list of supported timezones see:
     * https://www.php.net/manual/en/timezones.php.
     *
     * Default value: The server's timezone
     */
    'timezone' => DI\env('TIMEZONE', date_default_timezone_get()),

    /**
     * The maximum file size (in bytes) that can be hashed. This helps to
     * prevent timeouts for excessively large files.
     *
     * Set to 0 to disable hashing.
     *
     * Default value: Maximum 32-bit integer value.
     */
    'max_hash_size' => DI\env('MAX_HASH_SIZE', 2147483647),

    /**
     * Hashes to support in file info.
     *
     * Set to empty string to disable hashing.
     *
     * Default value: md5, sha1, sha256
     */
    'supported_hashes' => DI\env('SUPPORTED_HASHES', 'md5, sha1, sha256'),
];
