<?php

namespace Tests\Unit\Bootstrap;

use App\Bootstrap\ConfigComposer;
use DI\Container;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigComposerTest extends TestCase
{
    public function test_it_can_compose_the_config_component()
    {
        $container = new Container();
        (new ConfigComposer($container))();

        $config = $container->get(Config::class);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertTrue($config->has('app'));
        $this->assertTrue($config->has('icons'));
        $this->assertTrue($config->has('view'));
    }
}
