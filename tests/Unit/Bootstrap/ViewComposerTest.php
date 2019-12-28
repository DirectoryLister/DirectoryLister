<?php

namespace Tests\Unit\Bootstrap;

use App\Bootstrap\ViewComposer;
use App\Bootstrap\ViewFunctions;
use DI\Container;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase;
use Slim\Views\Twig;

class ViewComposerTest extends TestCase
{
    public function test_it_can_compose_the_view_component()
    {
        $container = new Container();
        (new ViewComposer($container, new Config))();

        $twig = $container->get(Twig::class);

        $this->assertInstanceOf(Twig::class, $twig);
        $this->assertEquals('app/cache/views', $twig->getEnvironment()->getCache());

        $this->assertInstanceOf(
            ViewFunctions\Asset::class,
            $twig->getEnvironment()->getFunction('asset')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\Config::class,
            $twig->getEnvironment()->getFunction('config')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\Icon::class,
            $twig->getEnvironment()->getFunction('icon')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\SizeForHumans::class,
            $twig->getEnvironment()->getFunction('sizeForHumans')->getCallable()
        );
    }
}
