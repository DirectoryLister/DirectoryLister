<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Factories\ConverterFactory;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ConverterFactory::class)]
class ConverterFactoryTest extends TestCase
{
    #[Test]
    public function it_returns_a_converterfactory(): void
    {
        $converter = (new ConverterFactory)();

        $this->assertInstanceOf(MarkdownConverter::class, $converter);
    }
}
