<?php

return array(

    // Basic settings
    'home_label'                => 'Home',
    'hide_dot_files'            => true,
    'list_folders_first'        => true,
    'list_sort_order'           => 'natcasesort',
    'theme_name'                => 'bootstrap',
    'date_format'               => 'Y-m-d H:i:s', // See: http://php.net/manual/en/function.date.php

    // Hidden files
    'hidden_files' => array(
        '.ht*',
        '*/.ht*',
        'resources',
        'resources/*',
        'analytics.inc',
        'header.php',
        'footer.php'
    ),

    // If set to 'true' an directory with an index file (as defined below) will
    // become a direct link to the index page instead of a browsable directory
    'links_dirs_with_index' => false,

    // Make linked directories open in a new (_blank) tab
    'external_links_new_window' => true,

    // Files that, if present in a directory, make the directory
    // a direct link rather than a browse link.
    'index_files' => array(
        'index.htm',
        'index.html',
        'index.php'
    ),

    // File hashing threshold
    'hash_size_limit' => 268435456, // 256 MB

    // Custom sort order
    'reverse_sort' => array(
        // 'path/to/folder'
    ),

    // Allow to download directories as zip files
    'zip_dirs' => false,

    // Stream zip file content directly to the client,
    // without any temporary file
    'zip_stream' => true,

    'zip_compression_level' => 0,

    // Disable zip downloads for particular directories
    'zip_disable' => array(
        // 'path/to/folder'
    ),

);
