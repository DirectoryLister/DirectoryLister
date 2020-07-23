<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Config;
use Tests\TestCase;

/** @covers \App\ViewFunctions\Config */
class ConfigTest extends TestCase
{
    public function test_it_can_retrieve_a_config_item(): void
    {
        $this->container->set('foo', 'Test value; please ignore');

        $config = new Config($this->config);

        $this->assertEquals('Test value; please ignore', $config('foo'));
    }

    public function test_it_returns_a_default_value(): void
    {
        $config = new Config($this->config);

        $this->assertEquals('Default value', $config('bar', 'Default value'));
    }
}
