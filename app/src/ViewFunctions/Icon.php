<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Config;
use Symfony\Component\Finder\SplFileInfo;
use Twig\Markup;

class Icon extends ViewFunction
{
    protected string $name = 'icon';

    public function __construct(
        private Config $config
    ) {}

    /** Retrieve the icon markup for a file. */
    public function __invoke(SplFileInfo $file): Markup
    {
        $icons = $this->config->get('icons');

        $icon = $file->isDir() ? 'fas fa-folder' : $icons[strtolower($file->getExtension())] ?? 'fas fa-file';

        return new Markup(sprintf('<i class="%s fa-fw fa-lg"></i>', $icon), 'UTF-8');
    }
}
