<?php

namespace Tests\Unit\Bootstrap\ViewFunctions;

use App\Bootstrap\ViewFunctions\Config;
use PHLAK\Config\Config as AppConfig;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /** @var AppConfig Application config */
    protected $config;

    public function setUp(): void
    {
        $this->config = new AppConfig([
            'foo' => false,
            'bar' => 'Red herring',
            'view' => [
                'foo' => 'Test value; please ignore'
            ],
        ]);
    }

    public function test_it_can_retrieve_a_config_item(): void
    {
        $config = new Config($this->config);

        $this->assertEquals('Test value; please ignore', $config('foo'));
    }

    public function test_it_returns_a_default_value(): void
    {
        $config = new Config($this->config);

        $this->assertEquals('Default value', $config('bar', 'Default value'));
    }
}
