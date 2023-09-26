<?php

namespace App\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class ErrorHandler implements ErrorHandlerInterface
{
    /** Create a new ErrorHandler object. */
    public function __construct(
        private Twig $view,
        private TranslatorInterface $translator
    ) {}

    /** Invoke the ErrorHandler class. */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $response = (new Response)->withStatus(500);

        if (in_array('application/json', explode(',', $request->getHeaderLine('Accept')))) {
            $response->getBody()->write((string) json_encode([
                'error' => ['message' => $this->translator->trans('error.unexpected')],
            ]));

            return $response->withHeader('Content-Type', 'application/json');
        }

        return $this->view->render($response, 'error.twig', [
            'message' => $this->translator->trans('error.unexpected'),
            'subtext' => $this->translator->trans('enable_debugging'),
        ]);
    }
}
