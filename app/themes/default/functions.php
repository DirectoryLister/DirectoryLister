<?php

use Symfony\Component\Finder\SplFileInfo;
use Twig\TwigFunction;

return [
    /** Theme specific Twig functions */
    'functions' => [
        /**
         * Return the icon markup for a given file.
         *
         * @param SplFileInfo $file
         *
         * @return string
         */
        new TwigFunction('icon', function (SplFileInfo $file) {
            $icons = require __DIR__ . '/icons.php';

            $icon = $file->isDir() ? 'fas fa-folder'
                : $icons[$file->getExtension()] ?? 'fas fa-file';

            return "<i class=\"{$icon} fa-fw fa-lg\"></i>";
        }),

        /**
         * Retrieve an item from the theme config.
         *
         * @param string     $key
         * @param mixed|null $default
         *
         * @return mixed
         */
        new TwigFunction('config', function (string $key, $default = null) {
            $config = require __DIR__ . '/config.php';

            foreach (explode('.', $key) as $k) {
                if (! isset($config[$k])) {
                    return $default;
                }
                $config = $config[$k];
            }

            return $config;
        }),
    ]
];
