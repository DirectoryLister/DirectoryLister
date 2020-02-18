<?php

namespace Tests\Providers;

use App\Providers\ConfigProvider;
use PHLAK\Config\Config;
use Tests\TestCase;

class ConfigProviderTest extends TestCase
{
    public function test_it_can_compose_the_config_component(): void
    {
        (new ConfigProvider($this->container))();

        $config = $this->container->get(Config::class);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertTrue($config->has('app'));
        $this->assertTrue($config->has('icons'));
    }
}
