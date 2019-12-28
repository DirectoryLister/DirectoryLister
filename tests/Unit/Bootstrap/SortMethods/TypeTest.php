<?php

namespace Tests\Unit\Bootstrap\SortMethods;

use App\Bootstrap\SortMethods\Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class TypeTest extends TestCase
{
    public function test_it_can_sort_by_file_type(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByType');

        (new Type)($finder);
    }
}
