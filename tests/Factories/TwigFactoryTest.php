<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Factories\TwigFactory;
use App\Functions;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(TwigFactory::class)]
class TwigFactoryTest extends TestCase
{
    #[Test]
    public function it_can_compose_the_view_component(): void
    {
        $this->container->set('view_cache', 'app/cache/views');

        $twig = $this->container->call(TwigFactory::class);

        $this->assertInstanceOf(Twig::class, $twig);
        $this->assertEquals('app/cache/views', $twig->getEnvironment()->getCache());
    }

    #[Test]
    public function it_registers_the_view_functions(): void
    {
        $twig = $this->container->call(TwigFactory::class);

        $this->assertInstanceOf(
            Functions\Analytics::class,
            $twig->getEnvironment()->getFunction('analytics')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\Breadcrumbs::class,
            $twig->getEnvironment()->getFunction('breadcrumbs')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\Config::class,
            $twig->getEnvironment()->getFunction('config')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\FileUrl::class,
            $twig->getEnvironment()->getFunction('file_url')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\Icon::class,
            $twig->getEnvironment()->getFunction('icon')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\Markdown::class,
            $twig->getEnvironment()->getFunction('markdown')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\ModifiedTime::class,
            $twig->getEnvironment()->getFunction('modified_time')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\ParentUrl::class,
            $twig->getEnvironment()->getFunction('parent_url')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\SizeForHumans::class,
            $twig->getEnvironment()->getFunction('size_for_humans')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\Translate::class,
            $twig->getEnvironment()->getFunction('translate')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\Url::class,
            $twig->getEnvironment()->getFunction('url')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\Vite::class,
            $twig->getEnvironment()->getFunction('vite')?->getCallable()
        );

        $this->assertInstanceOf(
            Functions\ZipUrl::class,
            $twig->getEnvironment()->getFunction('zip_url')?->getCallable()
        );
    }
}
