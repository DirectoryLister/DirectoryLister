<?php

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

abstract class SortMethod
{
    /**
     * Run the sort method.
     *
     * @param \Symfony\Component\Finder\Finder $finder
     *
     * @return void
     */
    abstract public function __invoke(Finder $finder): void;
}
