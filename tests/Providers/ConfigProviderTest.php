<?php

namespace Tests\Providers;

use App\Providers\ConfigProvider;
use PHLAK\Config\Interfaces\ConfigInterface;
use Tests\TestCase;

class ConfigProviderTest extends TestCase
{
    public function test_it_can_compose_the_config_component(): void
    {
        (new ConfigProvider($this->container))();

        $config = $this->container->get(ConfigInterface::class);

        $this->assertInstanceOf(ConfigInterface::class, $config);
        $this->assertTrue($config->has('app'));
        $this->assertTrue($config->has('icons'));
    }
}
