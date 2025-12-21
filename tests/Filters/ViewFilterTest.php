<?php

declare(strict_types=1);

namespace Tests\Filters;

use App\Filters\ViewFilter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ViewFilter::class)]
class ViewFilterTest extends TestCase
{
    #[Test]
    public function it_can_be_extended(): void
    {
        $viewFilter = new class extends ViewFilter {
            public string $name = 'foo';
        };

        $this->assertInstanceOf(ViewFilter::class, $viewFilter);
        $this->assertEquals('foo', $viewFilter->name);
    }
}
