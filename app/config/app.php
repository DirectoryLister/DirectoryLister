<?php

return [
    /**
     * Sorting order of files and folders.
     *
     * Possible values: type, natural, name, accessed, changed, modified, <callable>
     * Default value: type
     */
    'sort_order' => 'type',

    /**
     * Reverse the sort order.
     *
     * Possible values: true, false
     * Default value: false
     */
    'reverse_sort' => false,

    /**
     * Name of the theme to use for styling the application.
     *
     * Default value: default
     */
    'theme' => 'default',

    /**
     * Description here...
     *
     * Default value: 'Y-m-d H:i:s'
     */
    'date_format' => 'Y-m-d H:i:s',

    /**
     * Whether or not to hide application files/directories form the listing.
     *
     * Default value: true
     */
    'hide_app_files' => true,

    /**
     * Description here...
     *
     * Default value: []
     */
    'hidden_files' => [
        // ...
    ],

    /**
     * Hide version control system files (e.g. .git directories) from listing.
     *
     * Default value: true
     */
    'ignore_vcs_files' => true,
];
