<?php

namespace Tests\Unit\Bootstrap;

use App\Bootstrap\ViewComposer;
use App\Bootstrap\ViewFunctions;
use PHLAK\Config\Config;
use Slim\Views\Twig;
use Tests\TestCase;

class ViewComposerTest extends TestCase
{
    public function test_it_can_compose_the_view_component(): void
    {
        (new ViewComposer($this->container, new Config))();

        $twig = $this->container->get(Twig::class);

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
