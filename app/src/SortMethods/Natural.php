<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Natural extends SortMethod
{
    /** Sort by (natural) file name. */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByName(true);
    }
}
