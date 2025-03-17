<?php

declare(strict_types=1);

namespace Tests\ViewFunctions;

use App\ViewFunctions\Config;
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

        $config = new Config($this->config);

        $this->assertEquals('Test value; please ignore', $config('foo'));
    }

    #[Test]
    public function it_returns_a_default_value(): void
    {
        $config = new Config($this->config);

        $this->assertEquals('Default value', $config('bar', 'Default value'));
    }
}
