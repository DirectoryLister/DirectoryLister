<?php

namespace App\Handlers;

use PHLAK\Config\Config;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Translation\TranslatorInterface;

class DirectoryHandler
{
    /** @var Config App configuration component */
    protected $config;

    /** @var Finder File finder component */
    protected $finder;

    /** @var Twig Twig templating component */
    protected $view;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /**
     * Create a new IndexController object.
     *
     * @param \PHLAK\Config\Config                               $config
     * @param \Symfony\Component\Finder\Finder                   $finder
     * @param \Slim\Views\Twig                                   $view
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        Config $config,
        Finder $finder,
        Twig $view,
        TranslatorInterface $translator
    ) {
        $this->config = $config;
        $this->finder = $finder;
        $this->view = $view;
        $this->translator = $translator;
    }

    /**
     * Invoke the IndexController.
     *
     * @param \Slim\Psr7\Request  $request
     * @param \Slim\Psr7\Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $request->getQueryParams()['dir'] ?? '.';

        try {
            $files = $this->finder->in($path)->depth(0);
        } catch (DirectoryNotFoundException $exception) {
            return $this->view->render($response->withStatus(404), 'error.twig', [
                'message' => $this->translator->trans('error.directory_not_found')
            ]);
        }

        return $this->view->render($response, 'index.twig', [
            'files' => $files,
            'path' => $path,
            'readme' => $this->readme($files),
            'title' => $path == '.' ? 'Home' : $path,
        ]);
    }

    /**
     * Return the README file within a finder object.
     *
     * @param \Symfony\Component\Finder\Finder $files
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    protected function readme(Finder $files): ?SplFileInfo
    {
        if (! $this->config->get('app.display_readmes', true)) {
            return null;
        }

        $readmes = (clone $files)->name('/^README(?:\..+)?$/i');

        $readmes->filter(function (SplFileInfo $file) {
            return (bool) preg_match('/text\/.+/', mime_content_type($file->getPathname()));
        })->sort(function (SplFileInfo $file1, SplFileInfo $file2) {
            return $file1->getExtension() <=> $file2->getExtension();
        });

        if (! $readmes->hasResults()) {
            return null;
        }

        return $readmes->getIterator()->current();
    }
}
