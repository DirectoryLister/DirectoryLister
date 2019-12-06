<?php

namespace App\Controllers;

use PHLAK\Config\Config;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tightenco\Collect\Support\Collection;

class DirectoryController
{
    /** @var Config App configuration component */
    protected $config;

    /** @var Twig Twig templating component */
    protected $view;

    /** @var Collection Collection of hidden file paths */
    protected $hiddenFiles;

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

        $this->hiddenFiles = Collection::make(
            $this->config->get('hidden_files', [])
        )->map(function (string $file) {
            return glob($file, GLOB_BRACE | GLOB_NOSORT);
        })->flatten()->map(function (string $file) {
            return realpath($file);
        });
    }

    /**
     * Invoke the DirectoryController.
     *
     * @param \Slim\Psr7\Request  $request
     * @param \Slim\Psr7\Response $response
     * @param string              $path
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, string $path = '.')
    {
        $files = Finder::create()->in($path)->depth(0)->followLinks();
        $files->ignoreVCS($this->config->get('ignore_vcs_files', false));
        $files->filter(function (SplFileInfo $file) {
            return ! $this->hiddenFiles->contains($file->getRealPath());
        });
        $files->sortByName(true)->sortByType();

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
        $breadcrumbs = Collection::make(array_filter(explode('/', $path)));

        return $breadcrumbs->filter(function (string $crumb) {
            return $crumb !== '.';
        })->reduce(function (array $carry, string $crumb) {
            $carry[$crumb] = end($carry) . "/{$crumb}";

            return $carry;
        }, []);
    }
}
