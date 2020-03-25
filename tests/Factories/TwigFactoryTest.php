<?php

namespace Tests\Factories;

use App\ViewFunctions;
use Invoker\CallableResolver;
use Slim\Views\Twig;
use Tests\TestCase;

class TwigFactoryTest extends TestCase
{
    public function test_it_can_compose_the_view_component(): void
    {
        $this->container->set('view_cache', 'app/cache/views');
        $callableResolver = $this->container->get(CallableResolver::class);

        $twig = $this->container->get(Twig::class);

        $this->assertInstanceOf(Twig::class, $twig);
        $this->assertEquals('app/cache/views', $twig->getEnvironment()->getCache());
    }

    public function test_it_registers_the_view_functions(): void
    {
        $twig = $this->container->get(Twig::class);

        $this->assertInstanceOf(
            ViewFunctions\Asset::class,
            $twig->getEnvironment()->getFunction('asset')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\Breadcrumbs::class,
            $twig->getEnvironment()->getFunction('breadcrumbs')->getCallable()
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
            ViewFunctions\Markdown::class,
            $twig->getEnvironment()->getFunction('markdown')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\ParentDir::class,
            $twig->getEnvironment()->getFunction('parent_dir')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\SizeForHumans::class,
            $twig->getEnvironment()->getFunction('sizeForHumans')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\Translate::class,
            $twig->getEnvironment()->getFunction('translate')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\Url::class,
            $twig->getEnvironment()->getFunction('url')->getCallable()
        );
    }
}
