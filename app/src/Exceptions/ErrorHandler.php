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
    /** @var Twig Twig templating component */
    protected $view;

    /** @var TranslatorInterface Translation component */
    protected $translator;

    /**
     * Create a new ErrorHandler object.
     *
     * @param \Slim\Views\Twig                                   $view
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(Twig $view, TranslatorInterface $translator)
    {
        $this->view = $view;
        $this->translator = $translator;
    }

    /**
     * Invoke the ErrorHandler class.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Throwable                               $exception
     * @param bool                                     $displayErrorDetails
     * @param bool                                     $logErrors
     * @param bool                                     $logErrorDetails
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $response = (new Response)->withStatus(500);

        if (in_array('application/json', explode(',', $request->getHeaderLine('Accept')))) {
            $response->getBody()->write(json_encode([
                'error' => ['message' => $this->translator->trans('error.unexpected')]
            ]));

            return $response->withHeader('Content-Type', 'application/json');
        }

        return $this->view->render($response, 'error.twig', [
            'message' => $this->translator->trans('error.unexpected'),
            'subtext' => $this->translator->trans('enable_debugging'),
        ]);
    }
}
