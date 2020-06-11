<?php

namespace Tests\SortMethods;

use App\SortMethods\Natural;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/** @covers \App\SortMethods\Natural */
class NaturalTest extends TestCase
{
    public function test_it_can_sort_by_natural_file_name(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByName')->with(true);

        (new Natural)($finder);
    }
}
