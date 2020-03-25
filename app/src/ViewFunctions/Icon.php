<?php

namespace App\ViewFunctions;

use DI\Container;
use Symfony\Component\Finder\SplFileInfo;

class Icon extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'icon';

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new Config object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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
        $icons = $this->container->get('icons');

        $icon = $file->isDir() ? 'fas fa-folder'
            : $icons[$file->getExtension()] ?? 'fas fa-file';

        return "<i class=\"{$icon} fa-fw fa-lg\"></i>";
    }
}
