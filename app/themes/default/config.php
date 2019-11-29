<?php

use Symfony\Component\Finder\SplFileInfo;
use Twig\TwigFunction;

return [
    /** Theme specific Twig functions */
    'functions' => [
        new TwigFunction('icon', function (SplFileInfo $file) {
            $icons = require __DIR__ . '/icons.php';

            $icon = $file->isDir() ? 'fas fa-folder'
                : $icons[$file->getExtension()] ?? 'fas fa-file';

            return "<i class=\"{$icon} fa-fw fa-lg\"></i>";
        })
    ]
];
