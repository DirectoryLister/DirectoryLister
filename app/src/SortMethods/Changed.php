<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Changed extends SortMethod
{
    /** Sory by file changed time. */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByChangedTime();
    }
}
