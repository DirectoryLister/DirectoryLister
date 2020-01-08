<?php

use App\Support\Helpers;

return [
    /**
     * Sorting order of files and folders.
     *
     * Possible values: type, natural, name, accessed, changed, modified
     * Default value: type
     */
    'sort_order' => Helpers::env('SORT_ORDER'),

    /**
     * Reverse the sort order.
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
     * Hide version control system files (e.g. .git directories) from listing.
     *
     * Default value: true
     */
    'hide_vcs_files' => Helpers::env('HIDE_VSC_FILES'),

    /**
     * Parse and render README files on the page.
     *
     * Default value: false
     */
    'display_readmes' => Helpers::env('DISPLAY_READMES'),

    /**
     * The maximum file size (in bytes) that can be hashed. This helps to
     * prevent timeouts for excessively large files.
     *
     * Defualt value: 1000000000
     */
    'max_hash_size' => Helpers::env('MAX_HASH_SIZE'),

    /**
     * Additional providers to be loaded during application initialization.
     *
     * Default value: []
     */
    'providers' => [
        // ...
    ],
];
