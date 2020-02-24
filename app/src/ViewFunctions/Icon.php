<?php

namespace App\ViewFunctions;

use PHLAK\Config\Config;
use Symfony\Component\Finder\SplFileInfo;

class Icon extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'icon';

    /** @var \PHLAK\Config\Config */
    protected $config;

    /**
     * Create a new Config object.
     *
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Retrieve the icon markup for a file.
     *
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return string
     */
    public function __invoke(SplFileInfo $file): string
    {
        $iconConfig = $this->config->split('icons');

        $icon = $file->isDir() ? 'fas fa-folder'
            : $iconConfig->get($file->getExtension(), 'fas fa-file');

        return "<i class=\"{$icon} fa-fw fa-lg\"></i>";
    }
}
