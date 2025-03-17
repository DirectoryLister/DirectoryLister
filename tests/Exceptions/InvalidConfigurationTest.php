<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use App\Exceptions\InvalidConfiguration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(InvalidConfiguration::class)]
class InvalidConfigurationTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_from_a_config_value(): void
    {
        $exception = InvalidConfiguration::fromConfig('bar', 'foo');

        $this->assertInstanceOf(InvalidConfiguration::class, $exception);
        $this->assertEquals(
            "Unknown value 'foo' for configuration option 'bar'",
            $exception->getMessage()
        );
    }
}
