<?php

namespace App\ViewFunctions;

use App\Config;
use Symfony\Component\Finder\SplFileInfo;

class Icon extends ViewFunction
{
    protected string $name = 'icon';

    /** Create a new Config object. */
    public function __construct(
        private Config $config
    ) {}

    /** Retrieve the icon markup for a file. */
    public function __invoke(SplFileInfo $file): string
    {
        $icons = $this->config->get('icons');

        $icon = $file->isDir() ? 'fas fa-folder'
            : $icons[strtolower($file->getExtension())] ?? 'fas fa-file';

        return "<i class=\"{$icon} fa-fw fa-lg\"></i>";
    }
}
