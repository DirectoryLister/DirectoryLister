<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class RegisterGlobalsMiddleware
{
    /** Array of valid theme strings. */
    private const VALID_THEMES = ['dark', 'light'];

    public function __construct(
        private Twig $view
    ) {}

    /** Invoke the RegisterGlobalsMiddleware class. */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $this->view->getEnvironment()->addGlobal('theme', $this->getThemeFromRequest($request));

        return $handler->handle($request);
    }

    /** Determine the theme from the request. */
    private function getThemeFromRequest(Request $request): string
    {
        $cookies = $request->getCookieParams();

        if (! isset($cookies['theme'])) {
            return 'light';
        }

        if (! in_array($cookies['theme'], self::VALID_THEMES)) {
            return 'light';
        }

        return $cookies['theme'];
    }
}
