<?php

namespace App\ViewFunctions;

use Symfony\Component\Finder\SplFileInfo;

class Icon extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'icon';

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
