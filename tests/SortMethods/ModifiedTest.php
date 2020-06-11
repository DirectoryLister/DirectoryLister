<?php

namespace Tests\SortMethods;

use App\SortMethods\Modified;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/** @covers \App\SortMethods\Modified */
class ModifiedTest extends TestCase
{
    public function test_it_can_sort_by_modified_time(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByModifiedTime');

        (new Modified)($finder);
    }
}
