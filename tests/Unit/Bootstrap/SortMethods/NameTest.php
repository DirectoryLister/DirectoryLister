<?php

namespace Tests\Unit\Bootstrap\SortMethods;

use App\Bootstrap\SortMethods\Name;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class NameTest extends TestCase
{
    public function test_it_can_sort_by_file_name(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByName')->with(null);

        (new Name)($finder);
    }
}
