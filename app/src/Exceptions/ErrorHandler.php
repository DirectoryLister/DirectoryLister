<?php

namespace App\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Throwable;

class ErrorHandler implements ErrorHandlerInterface
{
    /** @const The default error message string */
    protected const DEFAULT_ERROR_MESSAGE = 'An unexpected error occured';

    /** @var Twig Twig templating component */
    protected $view;

    /**
     * Create a new ErrorHandler object.
     *
     * @param \Slim\Views\Twig $view
     */
    public function __construct(Twig $view)
    {
        $this->view = $view;
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
                'error' => ['message' => self::DEFAULT_ERROR_MESSAGE]
            ]));

            return $response->withHeader('Content-Type', 'application/json');
        }

        return $this->view->render($response, 'error.twig', [
            'message' => self::DEFAULT_ERROR_MESSAGE,
            'subtext' => 'Enable debugging for additional information',
        ]);
    }
}
