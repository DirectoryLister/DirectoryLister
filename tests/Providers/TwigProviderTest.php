<?php

namespace Tests\Providers;

use App\Providers\TwigProvider;
use App\ViewFunctions;
use Invoker\CallableResolver;
use PHLAK\Config\Config;
use Slim\Views\Twig;
use Tests\TestCase;

class TwigProviderTest extends TestCase
{
    public function test_it_can_compose_the_view_component(): void
    {
        $callableResolver = $this->container->get(CallableResolver::class);
        (new TwigProvider($this->container, new Config, $callableResolver))();

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
