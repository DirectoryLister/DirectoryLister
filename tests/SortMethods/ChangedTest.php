<?php

declare(strict_types=1);

namespace Tests\SortMethods;

use App\SortMethods\Changed;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(Changed::class)]
class ChangedTest extends TestCase
{
    #[Test]
    public function it_can_sort_by_changed_time(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByChangedTime');

        (new Changed)($finder);
    }
}
