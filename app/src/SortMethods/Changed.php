<?php

declare(strict_types=1);

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Changed extends SortMethod
{
    /** Sort by file changed time. */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByChangedTime();
    }
}
