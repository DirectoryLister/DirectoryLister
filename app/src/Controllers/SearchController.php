<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchController
{
    /** @var Finder File finder component */
    protected $finder;

    /** @var Twig Twig templating component */
    protected $view;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /** Create a new SearchHandler object. */
    public function __construct(Finder $finder, Twig $view, TranslatorInterface $translator)
    {
        $this->finder = $finder;
        $this->view = $view;
        $this->translator = $translator;
    }

    /** Invoke the SearchHandler. */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $search = $request->getQueryParams()['search'];

        $files = $this->finder->in('.')->name(
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
