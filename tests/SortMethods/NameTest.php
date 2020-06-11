<?php

namespace Tests\SortMethods;

use App\SortMethods\Name;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/** @covers \App\SortMethods\Name */
class NameTest extends TestCase
{
    public function test_it_can_sort_by_file_name(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByName')->with(null);

        (new Name)($finder);
    }
}
