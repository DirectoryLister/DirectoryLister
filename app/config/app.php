<?php

use App\Support\Helpers;

return [
    /**
     * Sorting order of files and folders.
     *
     * Possible values: type, natural, name, accessed, changed, modified
     * Default value: type
     */
    'sort_order' => Helpers::env('SORT_ORDER', 'type'),

    /**
     * Reverse the sort order.
     *
     * Default value: false
     */
    'reverse_sort' => Helpers::env('REVERSE_SORT', false),

    /**
     * Default date format.
     *
     * Default value: 'Y-m-d H:i:s'
     */
    'date_format' => Helpers::env('DATE_FORMAT', 'Y-m-d H:i:s'),

    /**
     * Description here...
     *
     * Default value: []
     */
    'hidden_files' => array_map(function (string $file) {
        return trim($file);
    }, explode(',', Helpers::env('HIDDEN_FILES'))),

    /**
     * Whether or not to hide application files/directories form the listing.
     *
     * Default value: true
     */
    'hide_app_files' => Helpers::env('HIDE_APP_FILES', true),

    /**
     * Hide version control system files (e.g. .git directories) from listing.
     *
     * Default value: true
     */
    'hide_vcs_files' => Helpers::env('HIDE_VSC_FILES', true),

    /**
     * The maximum file size (in bytes) that can be hashed. This helps to
     * prevent timeouts for excessively large files.
     *
     * Defualt value: 1000000000
     */
    'max_hash_size' => Helpers::env('MAX_HASH_SIZE', 1000000000)
];
