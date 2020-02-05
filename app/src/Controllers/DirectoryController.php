<?php

namespace App\Controllers;

use DI\Container;
use PHLAK\Config\Config;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DirectoryController
{
    /** @var Config App configuration component */
    protected $config;

    /** @var Container Application container */
    protected $container;

    /** @var Twig Twig templating component */
    protected $view;

    /**
     * Create a new DirectoryController object.
     *
     * @param \DI\Container        $container
     * @param \PHLAK\Config\Config $config
     * @param \Slim\Views\Twig     $view
     */
    public function __construct(Container $container, Config $config, Twig $view)
    {
        $this->container = $container;
        $this->config = $config;
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
        $search = $request->getQueryParams()['search'] ?? null;

        try {
            $files = $files->in($path);
        } catch (DirectoryNotFoundException $exception) {
            return $this->view->render($response->withStatus(404), '404.twig');
        }

        return $this->view->render($response, 'index.twig', [
            'files' => $search ? $files->name(
                sprintf('/(?:.*)%s(?:.*)/i', preg_quote($search, '/'))
            ) : $files->depth(0),
            'path' => $path,
            'readme' => $this->readme($path),
            'search' => $search,
        ]);
    }

    /**
     * Return the README file for a given path.
     *
     * @param string $path
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    protected function readme($path): ?SplFileInfo
    {
        if (! $this->config->get('app.display_readmes', true)) {
            return null;
        }

        $readmes = Finder::create()->in($path)->depth(0)->name('/^README(?:\..+)?$/i');
        $readmes->filter(function (SplFileInfo $file) {
            return (bool) preg_match('/text\/.+/', mime_content_type($file->getPathname()));
        });
        $readmes->sort(function (SplFileInfo $file1, SplFileInfo $file2) {
            return $file1->getExtension() <=> $file2->getExtension();
        });

        if (! $readmes->hasResults()) {
            return null;
        }

        $readmeArray = iterator_to_array($readmes);

        return array_shift($readmeArray);
    }
}
