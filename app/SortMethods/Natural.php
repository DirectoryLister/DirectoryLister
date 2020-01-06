<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Natural extends SortMethod
{
    /**
     * Sort by (natural) file name.
     *
     * @param \Symfony\Component\Finder\Finder $finder
     *
     * @return void
     */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByName(true);
    }
}
