<?php

return array(

    // Basic settings
    'base_directory'     => null,
    'hide_dot_files'     => true,
    'list_folders_first' => true,
    'list_sort_order'    => 'natcasesort',
    'theme_name'         => 'bootstrap',
    'date_format'        => 'Y-m-d H:i:s',
    'relative_paths'     => true,
    'directory_queries'  => true,

    // Index files
    'index_filenames' => array(
       'index.html',
       'index.php'
    ),

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
