<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchController
{
    public function __construct(
        private Container $container,
        private Finder $finder,
        private Twig $view,
        private TranslatorInterface $translator
    ) {}

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $search = $request->getQueryParams()['search'];

        $files = $this->finder->in($this->container->call('full_path', ['path' => '.']))->name(
            $search ? sprintf('/(?:.*)%s(?:.*)/i', preg_quote($search, '/')) : ''
        );

        if ($files->count() === 0) {
            return $this->view->render($response, 'error.twig', [
                'message' => $this->translator->trans('error.no_results_found'),
                'search' => $search,
            ]);
        }

        return $this->view->render($response, 'index.twig', [
            'files' => $files,
            'search' => $search,
            'title' => $search,
        ]);
    }
}
