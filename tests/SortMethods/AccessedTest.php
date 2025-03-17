<?php

declare(strict_types=1);

namespace Tests\SortMethods;

use App\SortMethods\Accessed;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(Accessed::class)]
class AccessedTest extends TestCase
{
    #[Test]
    public function it_can_sort_by_accessed_time(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByAccessedTime');

        (new Accessed)($finder);
    }
}
