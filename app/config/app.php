<?php

return [
    'list_folders_first' => true,

    'list_sort_order' => 'natcasesort',

    'theme' => 'default',

    'date_format' => 'Y-m-d H:i:s',

    'hidden_files' => [
        'some.file', // Files
        'some_dir',  // Directories
        '**/.ht*',   // Globs
        '/^.ht*$/',  // Regular expressions
    ],
];
