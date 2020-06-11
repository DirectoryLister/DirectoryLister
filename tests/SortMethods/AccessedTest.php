<?php

namespace Tests\SortMethods;

use App\SortMethods\Accessed;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/** @covers \App\SortMethods\Accessed */
class AccessedTest extends TestCase
{
    public function test_it_can_sort_by_accessed_time(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByAccessedTime');

        (new Accessed)($finder);
    }
}
