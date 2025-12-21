<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\ViewFunction;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ViewFunction::class)]
class ViewFunctionTest extends TestCase
{
    #[Test]
    public function it_can_be_extended(): void
    {
        $viewFunction = new class extends ViewFunction {
            public string $name = 'foo';
        };

        $this->assertInstanceOf(ViewFunction::class, $viewFunction);
        $this->assertEquals('foo', $viewFunction->name);
    }
}
