<?php

namespace Tests\Unit\Bootstrap\SortMethods;

use App\Bootstrap\SortMethods\Changed;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class ChangedTest extends TestCase
{
    public function test_it_can_sort_by_changed_time(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByChangedTime');

        (new Changed)($finder);
    }
}
