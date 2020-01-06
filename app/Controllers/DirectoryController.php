<?php

namespace App\Controllers;

use DI\Container;
use Parsedown;
use PHLAK\Config\Config;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Tightenco\Collect\Support\Collection;

class DirectoryController
{
    /** @var Config App configuration component */
    protected $config;

    /** @var Container Application container */
    protected $container;

    /** @var Parsedown Parsedown component */
    protected $parsedown;

    /** @var Twig Twig templating component */
    protected $view;

    /**
     * Create a new DirectoryController object.
     *
     * @param \DI\Container        $container
     * @param \PHLAK\Config\Config $config
     * @param \Slim\Views\Twig     $view
     */
    public function __construct(
        Container $container,
        Config $config,
        Parsedown $parsedown,
        Twig $view
    ) {
        $this->container = $container;
        $this->config = $config;
        $this->parsedown = $parsedown;
        $this->view = $view;
    }

    /**
     * Invoke the DirectoryController.
     *
     * @param \Symfony\Component\Finder\Finder $files
     * @param \Slim\Psr7\Response              $response
     * @param string                           $path
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(
        Finder $files,
        Request $request,
        Response $response,
        string $path = '.'
    ) {
        $path = realpath($this->container->get('base_path') . '/' . $path);

        try {
            $files = $files->in($path);
        } catch (DirectoryNotFoundException $exception) {
            return $this->view->render($response->withStatus(404), '404.twig');
        }

        if ($search = $request->getQueryParams()['search'] ?? false) {
            $files->name(
                sprintf('/(?:.*)%s(?:.*)/i', preg_quote($search, '/'))
            );
        } else {
            $files->depth(0);
        }

        $readmeFiles = Finder::create()->in($path)->depth(0)->name('/^README\.md$/i');
        if ($readmeFiles->hasResults() && ! $search) {
            $readmeArray = iterator_to_array($readmeFiles);
            $readme = $this->parsedown->parse(
                array_shift($readmeArray)->getContents()
            );
        }

        return $this->view->render($response, 'index.twig', [
            'breadcrumbs' => $this->breadcrumbs($path),
            'files' => $files,
            'is_root' => $this->isRoot($path),
            'search' => $search ?? null,
            'readme' => $readme ?? null,
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
        $breadcrumbs = Collection::make(explode('/', $path))->diff(
            explode('/', $this->container->get('base_path'))
        )->filter();

        return $breadcrumbs->filter(function (string $crumb) {
            return $crumb !== '.';
        })->reduce(function (array $carry, string $crumb) {
            $carry[$crumb] = end($carry) . "/{$crumb}";

            return $carry;
        }, []);
    }

    /**
     * Determine if a provided path is the root path.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function isRoot(string $path): bool
    {
        return realpath($path) === realpath($this->container->get('base_path'));
    }
}
