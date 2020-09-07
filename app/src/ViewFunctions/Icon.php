<?php

namespace App\ViewFunctions;

use App\Config;
use Symfony\Component\Finder\SplFileInfo;

class Icon extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'icon';

    /** @var Config The application configuration */
    protected $config;

    /** Create a new Config object. */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /** Retrieve the icon markup for a file. */
    public function __invoke(SplFileInfo $file): string
    {
        $icons = $this->config->get('icons');

        $icon = $file->isDir() ? 'fas fa-folder'
            : $icons[$file->getExtension()] ?? 'fas fa-file';

        return "<i class=\"{$icon} fa-fw fa-lg\"></i>";
    }
}
