<?php

namespace Tests\Exceptions;

use App\Exceptions\InvalidConfiguration;
use Tests\TestCase;

/** @covers \App\Exceptions\InvalidConfiguration */
class InvalidConfigurationTest extends TestCase
{
    public function test_it_can_be_instantiated_from_a_config_value(): void
    {
        $exception = InvalidConfiguration::fromConfig('bar', 'foo');

        $this->assertInstanceOf(InvalidConfiguration::class, $exception);
        $this->assertEquals(
            "Unknown value 'foo' for configuration option 'bar'",
            $exception->getMessage()
        );
    }
}
