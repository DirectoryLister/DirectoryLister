<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Accessed extends SortMethod
{
    /** Sort by file accessed time. */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByAccessedTime();
    }
}
