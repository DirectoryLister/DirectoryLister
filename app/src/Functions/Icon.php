<?php

declare(strict_types=1);

namespace App\Functions;

use DI\Attribute\Inject;
use Symfony\Component\Finder\SplFileInfo;
use Twig\Markup;

class Icon extends ViewFunction
{
    public string $name = 'icon';

    #[Inject('icons')]
    private array $icons;

    /** Retrieve the icon markup for a file. */
    public function __invoke(SplFileInfo $file): Markup
    {
        $icon = $file->isDir() ? 'fas fa-folder' : $this->icons[strtolower($file->getExtension())] ?? 'fas fa-file';

        return new Markup(sprintf('<i class="%s fa-fw fa-lg"></i>', $icon), 'UTF-8');
    }
}
