<?php

namespace Tests\Unit\Bootstrap;

use App\Bootstrap\FinderComposer;
use DI\Container;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class FinderComposerTest extends TestCase
{
    public function test_it_can_compose_the_finder_component()
    {
        $container = new Container();
        (new FinderComposer($container, new Config))();

        $finder = $container->get(Finder::class);

        $this->assertInstanceOf(Finder::class, $finder);
    }
}
