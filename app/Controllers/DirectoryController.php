<?php

namespace PHLAK\DirectoryLister\Controllers;

use PHLAK\DirectoryLister\DirectoryLister;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class DirectoryController
{
    public function __invoke(Request $request, Response $response, Twig $view, string $path = '.')
    {
        return $view->render($response, 'index.twig', [
            // TODO: Sort the file listing
            'files' => new DirectoryLister($path)
        ]);
    }
}
