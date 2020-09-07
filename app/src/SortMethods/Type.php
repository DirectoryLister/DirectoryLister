<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Type extends SortMethod
{
    /** Sory by file type. */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByType();
    }
}
