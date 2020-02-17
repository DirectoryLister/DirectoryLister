<?php

namespace App\Handlers;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;

class SearchHandler
{
    /** @var Finder File finder component */
    protected $finder;

    /** @var Twig Twig templating component */
    protected $view;

    /**
     * Create a new SearchHandler object.
     *
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Slim\Views\Twig                 $view
     */
    public function __construct(Finder $finder, Twig $view)
    {
        $this->finder = $finder;
        $this->view = $view;
    }

    /**
     * Invoke the SearchHandler.
     *
     * @param \Slim\Psr7\Request  $request
     * @param \Slim\Psr7\Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $search = $request->getQueryParams()['search'];

        $files = $this->finder->in('.')->name(
            sprintf('/(?:.*)%s(?:.*)/i', preg_quote($search, '/'))
        );

        return $this->view->render($response, 'index.twig', [
            'files' => $files,
            'search' => $search,
            'title' => $search,
        ]);
    }
}
