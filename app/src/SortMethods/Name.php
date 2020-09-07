<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Name extends SortMethod
{
    /** Sort by file name. */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByName();
    }
}
