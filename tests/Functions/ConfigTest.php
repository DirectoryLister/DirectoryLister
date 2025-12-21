<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Config::class)]
class ConfigTest extends TestCase
{
    #[Test]
    public function it_can_retrieve_a_config_item(): void
    {
        $this->container->set('foo', 'Test value; please ignore');

        $config = $this->container->make(Config::class);

        $this->assertEquals('Test value; please ignore', $config('foo'));
    }

    #[Test]
    public function it_returns_a_default_value(): void
    {
        $config = $this->container->make(Config::class);

        $this->assertEquals('Default value', $config('bar', 'Default value'));
    }
}
