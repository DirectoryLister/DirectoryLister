<?php

declare(strict_types=1);

namespace App\SortMethods;

use Symfony\Component\Finder\Finder;

abstract class SortMethod
{
    /** Run the sort method. */
    abstract public function __invoke(Finder $finder): void;
}
