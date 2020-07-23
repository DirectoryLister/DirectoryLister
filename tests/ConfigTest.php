<?php

namespace Tests;

class ConfigTest extends TestCase
{
    public function test_it_can_get_an_environment_variable(): void
    {
        $this->container->set('test_string', 'Test string; please ignore');

        $item = $this->config->get('test_string');

        $this->assertEquals('Test string; please ignore', $item);
    }

    public function test_it_can_return_a_default_value(): void
    {
        $item = $this->config->get('defualt_test', 'Test default; please ignore');

        $this->assertEquals('Test default; please ignore', $item);
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

    public function test_it_can_be_surrounded_by_quotation_marks(): void
    {
        $this->container->set('quotes_test', '"Test quotes; please ignore"');

        $item = $this->config->get('quotes_test');

        $this->assertEquals('Test quotes; please ignore', $item);
    }
}
