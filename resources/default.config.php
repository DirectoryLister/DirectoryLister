<?php

return array(

    // Basic settings
    'hide_dot_files'     => true,
    'list_folders_first' => true,
    'list_sort_order'    => 'natcasesort',
    'theme_name'         => 'bootstrap',

    // Hidden files
    'hidden_files' => array(
        '*/.ht*',
        'resources',
        'resources/*',
        'analytics.inc'
    ),

    // File hashing threshold
    'hash_size_limit' => 268435456, // 256 MB

    // Custom sort order
    'reverse_sort' => array(
        // 'path/to/folder'
    )

);
