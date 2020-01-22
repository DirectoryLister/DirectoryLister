<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

class Changed extends SortMethod
{
    /**
     * Sory by file changed time.
     *
     * @param \Symfony\Component\Finder\Finder $finder
     *
     * @return void
     */
    public function __invoke(Finder $finder): void
    {
        $finder->sortByChangedTime();
    }
}
