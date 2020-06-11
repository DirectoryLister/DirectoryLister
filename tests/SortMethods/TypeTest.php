<?php

namespace Tests\SortMethods;

use App\SortMethods\Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/** @covers \App\SortMethods\Type */
class TypeTest extends TestCase
{
    public function test_it_can_sort_by_file_type(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByType');

        (new Type)($finder);
    }
}
