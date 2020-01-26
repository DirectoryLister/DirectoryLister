<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Modified extends SortMethod
{
    /**
     * Sort by file modified time.
     *
     * @param \Symfony\Component\Finder\Finder $finder
     *
     * @return void
     */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByModifiedTime();
    }
}
