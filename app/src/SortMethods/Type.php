<?php

declare(strict_types=1);

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Type extends SortMethod
{
    /** Sort by file type. */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByType();
    }
}
