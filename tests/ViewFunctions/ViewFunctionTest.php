<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\ViewFunction;
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
            protected string $name = 'foo';
        };

        $this->assertInstanceOf(ViewFunction::class, $viewFunction);
        $this->assertEquals('foo', $viewFunction->name());
    }
}
