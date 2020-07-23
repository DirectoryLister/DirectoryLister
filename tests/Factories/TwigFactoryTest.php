<?php

namespace Tests\Factories;

use App\Factories\TwigFactory;
use App\ViewFunctions;
use Invoker\CallableResolver;
use Slim\Views\Twig;
use Tests\TestCase;

/** @covers \App\Factories\TwigFactory */
class TwigFactoryTest extends TestCase
{
    public function test_it_can_compose_the_view_component(): void
    {
        $this->container->set('view_cache', 'app/cache/views');
        $callableResolver = $this->container->get(CallableResolver::class);

        $twig = (new TwigFactory($this->config, $callableResolver))();

        $this->assertInstanceOf(Twig::class, $twig);
        $this->assertEquals('app/cache/views', $twig->getEnvironment()->getCache());
    }

    public function test_it_registers_the_view_functions(): void
    {
        $callableResolver = $this->container->get(CallableResolver::class);

        $twig = (new TwigFactory($this->config, $callableResolver))();

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
            ViewFunctions\FileUrl::class,
            $twig->getEnvironment()->getFunction('file_url')->getCallable()
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
            ViewFunctions\ParentUrl::class,
            $twig->getEnvironment()->getFunction('parent_url')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\SizeForHumans::class,
            $twig->getEnvironment()->getFunction('size_for_humans')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\Translate::class,
            $twig->getEnvironment()->getFunction('translate')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\Url::class,
            $twig->getEnvironment()->getFunction('url')->getCallable()
        );

        $this->assertInstanceOf(
            ViewFunctions\ZipUrl::class,
            $twig->getEnvironment()->getFunction('zip_url')->getCallable()
        );
    }
}
