<?php

declare(strict_types=1);

namespace Tests\SortMethods;

use App\SortMethods\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(Type::class)]
class TypeTest extends TestCase
{
    #[Test]
    public function it_can_sort_by_file_type(): void
    {
        $finder = $this->createMock(Finder::class);
        $finder->expects($this->once())->method('sortByType');

        (new Type)($finder);
    }
}
