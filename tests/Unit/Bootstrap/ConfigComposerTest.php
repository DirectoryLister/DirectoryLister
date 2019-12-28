<?php

namespace Tests\Unit\Bootstrap;

use App\Bootstrap\ConfigComposer;
use PHLAK\Config\Config;
use Tests\TestCase;

class ConfigComposerTest extends TestCase
{
    public function test_it_can_compose_the_config_component(): void
    {
        (new ConfigComposer($this->container))();

        $config = $this->container->get(Config::class);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertTrue($config->has('app'));
        $this->assertTrue($config->has('icons'));
        $this->assertTrue($config->has('view'));
    }
}
