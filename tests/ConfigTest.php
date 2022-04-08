<?php

namespace Tests;

/** @covers \App\Config */
class ConfigTest extends TestCase
{
    public function test_it_can_retrieve_a_preset_configuration_value(): void
    {
        $this->container->set('test_string', 'Test string; please ignore');

        $string = $this->config->get('test_string');

        $this->assertEquals('Test string; please ignore', $string);
    }

    public function test_it_returns_a_default_value(): void
    {
        $default = $this->config->get('defualt_test', 'Test default; please ignore');

        $this->assertEquals('Test default; please ignore', $default);
    }

    public function test_it_can_retrieve_a_boolean_value(): void
    {
        $this->container->set('test_true', 'true');
        $this->container->set('test_false', 'false');

        $this->assertTrue($this->config->get('test_true'));
        $this->assertFalse($this->config->get('test_false'));
    }

    public function test_it_can_retrieve_a_null_value(): void
    {
        $this->container->set('null_test', 'null');

        $this->assertNull($this->config->get('null_test'));
    }

    public function test_it_can_retrieve_an_array_value(): void
    {
        $this->container->set('array_test', ['foo', 'bar', 'baz']);

        $array = $this->config->get('array_test');

        $this->assertEquals(['foo', 'bar', 'baz'], $array);
    }

    public function test_it_can_be_surrounded_by_quotation_marks(): void
    {
        $this->container->set('quotes_test', '"Test quotes; please ignore"');

        $item = $this->config->get('quotes_test');

        $this->assertEquals('Test quotes; please ignore', $item);
    }
}
