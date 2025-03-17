<?php

namespace Tests\SortMethods;

use App\SortMethods\Name;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(Name::class)]
class NameTest extends TestCase
{
    #[Test]
    public function it_can_sort_by_file_name(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByName')->with(null);

        (new Name)($finder);
    }
}
