<?php

namespace Tests\Middlewares;

use App\Middlewares\RegisterGlobalsMiddleware;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;
use Tests\TestCase;

/** @covers \App\Middlewares\RegisterGlobalsMiddleware */
class RegisterGlobalsMiddlewareTest extends TestCase
{
    /** @var Twig Twig templating component */
    protected $view;

    /** @var ServerRequestInterface&MockObject */
    protected $request;

    /** @var RequestHandlerInterface&MockObject */
    protected $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->view = $this->container->get(Twig::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
    }

    public function test_it_sets_the_theme_view_variabe_to_light_by_default(): void
    {
        (new RegisterGlobalsMiddleware($this->view))($this->request, $this->handler);

        $this->assertEquals(['theme' => 'light'], $this->view->getEnvironment()->getGlobals());
    }

    public function test_it_sets_the_theme_view_variabe_to_dark_when_the_theme_cookie_is_dark(): void
    {
        $this->request->expects($this->once())->method('getCookieParams')->willReturn([
            'theme' => 'dark',
        ]);

        (new RegisterGlobalsMiddleware($this->view))($this->request, $this->handler);

        $this->assertEquals(['theme' => 'dark'], $this->view->getEnvironment()->getGlobals());
    }

    public function test_it_sets_the_theme_view_variabe_to_light_when_the_theme_cookie_is_light(): void
    {
        $this->request->expects($this->once())->method('getCookieParams')->willReturn([
            'theme' => 'dark',
        ]);

        (new RegisterGlobalsMiddleware($this->view))($this->request, $this->handler);

        $this->assertEquals(['theme' => 'dark'], $this->view->getEnvironment()->getGlobals());
    }

    public function test_it_sets_the_theme_view_variabe_to_light_for_an_unknown_value(): void
    {
        $this->request->expects($this->once())->method('getCookieParams')->willReturn([
            'theme' => 'dim',
        ]);

        (new RegisterGlobalsMiddleware($this->view))($this->request, $this->handler);

        $this->assertEquals(['theme' => 'light'], $this->view->getEnvironment()->getGlobals());
    }
}
