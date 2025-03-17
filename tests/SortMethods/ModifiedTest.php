<?php

declare(strict_types=1);

namespace Tests\SortMethods;

use App\SortMethods\Modified;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(Modified::class)]
class ModifiedTest extends TestCase
{
    #[Test]
    public function it_can_sort_by_modified_time(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByModifiedTime');

        (new Modified)($finder);
    }
}
