<?php

namespace App\Controllers;

use PHLAK\Config\Config;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;

class DirectoryController
{
    /** @var Config App configuration component */
    protected $config;

    /** @var Twig Twig templating component */
    protected $view;

    /**
     * Create a new DirectoryController object.
     *
     * @param \PHLAK\Config\Config $config
     * @param \Slim\Views\Twig     $view
     */
    public function __construct(Config $config, Twig $view)
    {
        $this->config = $config;
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
        $files->exclude($this->config->get('hidden_files', []));
        $files->ignoreVCS($this->config->get('ignore_vcs_files', false));
        $files->sortByName(true)->sortByType();
        // TODO: Filter out hidden files

        return $this->view->render($response, 'index.twig', [
            'breadcrumbs' => $this->breadcrumbs($path),
            'files' => $files,
        ]);
    }

    /**
     * Build an array of breadcrumbs for a given path.
     *
     * @param string $path
     *
     * @return array
     */
    protected function breadcrumbs(string $path): array
    {
        $breadcrumbs = [];
        $crumbsPath = '';

        foreach (array_filter(explode('/', $path)) as $crumb) {
            $crumbsPath .= "/{$crumb}";

            $breadcrumbs[] = [
                'name' => $crumb,
                'path' => $crumbsPath
            ];
        }

        return array_filter($breadcrumbs, function ($crumb) {
            return $crumb['name'] !== '.';
        });
    }
}
