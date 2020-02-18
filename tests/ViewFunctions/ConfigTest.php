<?php

namespace Tests\ViewFunctions;

use App\ViewFunctions\Config;
use PHLAK\Config\Config as AppConfig;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    /** @var AppConfig Application config */
    protected $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = new AppConfig([
            'foo' => false,
            'bar' => 'Red herring',
            'app' => [
                'foo' => 'Test value; please ignore'
            ],
        ]);
    }

    public function test_it_can_retrieve_a_config_item(): void
    {
        $config = new Config($this->container, $this->config);

        $this->assertEquals('Test value; please ignore', $config('foo'));
    }

    public function test_it_returns_a_default_value(): void
    {
        $config = new Config($this->container, $this->config);

        $this->assertEquals('Default value', $config('bar', 'Default value'));
    }
}
