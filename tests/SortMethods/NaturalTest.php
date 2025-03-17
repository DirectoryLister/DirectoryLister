<?php

declare(strict_types=1);

namespace Tests\SortMethods;

use App\SortMethods\Natural;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(Natural::class)]
class NaturalTest extends TestCase
{
    #[Test]
    public function it_can_sort_by_natural_file_name(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByName')->with(true);

        (new Natural)($finder);
    }
}
