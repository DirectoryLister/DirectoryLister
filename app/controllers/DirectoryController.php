<?php

namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;

class DirectoryController
{
    /** @var Twig Twig templating component */
    protected $view;

    /**
     * Create a new DirectoryController object.
     *
     * @param \Slim\Views\Twig $view
     */
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    /**
     * Invoke the DirectoryController.
     *
     * @param \Slim\Psr7\Request  $request
     * @param \Slim\Psr7\Response $response
     * @param string              $path
     *
     * @return \Slim\Psr7\Response
     */
    public function __invoke(Request $request, Response $response, string $path = '.')
    {
        $files = new Finder();
        $files->in($path)->depth(0)->followLinks();
        $files->sortByName($useNatrualSort = true)->sortByType();

        return $this->view->render($response, 'index.twig', [
            'files' => $files
        ]);
    }
}
