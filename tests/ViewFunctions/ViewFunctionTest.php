<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\ViewFunction;
use Tests\TestCase;

/** @covers \App\ViewFunctions\ViewFunction */
class ViewFunctionTest extends TestCase
{
    public function test_it_can_be_extended(): void
    {
        $viewFunction = new class extends ViewFunction {
            protected string $name = 'foo';
        };

        $this->assertInstanceOf(ViewFunction::class, $viewFunction);
        $this->assertEquals('foo', $viewFunction->name());
    }
}
