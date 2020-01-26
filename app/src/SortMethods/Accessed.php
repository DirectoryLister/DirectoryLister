<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Accessed extends SortMethod
{
    /**
     * Sort by file accessed time.
     *
     * @param \Symfony\Component\Finder\Finder $finder
     *
     * @return void
     */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByAccessedTime();
    }
}
