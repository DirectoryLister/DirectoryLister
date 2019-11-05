<?php

return [
    /**
     * The root directory to list.
     *
     * Default value: '.'
     */
    'root' => '.',

    /**
     * Whether or not to list folders before regular files.
     *
     * Possible values: true, false
     * Default value: true
     */
    'list_folders_first' => true,

    /**
     * Sorting order of files.
     *
     * Possible values: foo, bar, baz
     * Default value: natcasesort
     */
    'sort_order' => 'natcasesort',

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
