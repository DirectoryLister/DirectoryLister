<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class ThemeMiddleware
{
    /** Array of valid theme strings. */
    private const VALID_THEMES = ['dark', 'light'];

    /** @var Twig Twig templating component */
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    /** Invoke the ThemeMiddleware class. */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $this->view->getEnvironment()->addGlobal('theme', $this->getThemeFromRequest($request));

        return $handler->handle($request);
    }

    /** Determine the theme from the request. */
    private function getThemeFromRequest(Request $request): string
    {
        if (! isset($request->getCookieParams()['theme'])) {
            return 'light';
        }

        if (! in_array($request->getCookieParams()['theme'], self::VALID_THEMES)) {
            return 'light';
        }

        return $request->getCookieParams()['theme'];
    }
}
