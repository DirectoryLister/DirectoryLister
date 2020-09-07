<?php

namespace App\Controllers;

use App\Config;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Translation\TranslatorInterface;

class DirectoryController
{
    /** @var Config The application configuration */
    protected $config;

    /** @var Finder File finder component */
    protected $finder;

    /** @var Twig Twig templating component */
    protected $view;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /** Create a new IndexController object. */
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

    /** Invoke the IndexController. */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $request->getQueryParams()['dir'] ?? '.';

        try {
            $files = $this->finder->in($path)->depth(0);
        } catch (Exception $exception) {
            return $this->view->render($response->withStatus(404), 'error.twig', [
                'message' => $this->translator->trans('error.directory_not_found'),
            ]);
        }

        return $this->view->render($response, 'index.twig', [
            'files' => $files,
            'path' => $path,
            'readme' => $this->readme($files),
            'title' => $path == '.' ? 'Home' : $path,
        ]);
    }

    /** Return the README file within a finder object. */
    protected function readme(Finder $files): ?SplFileInfo
    {
        if (! $this->config->get('display_readmes')) {
            return null;
        }

        $readmes = (clone $files)->name('/^README(?:\..+)?$/i');

        $readmes->filter(static function (SplFileInfo $file) {
            return (bool) preg_match('/text\/.+/', mime_content_type($file->getPathname()));
        })->sort(static function (SplFileInfo $file1, SplFileInfo $file2) {
            return $file1->getExtension() <=> $file2->getExtension();
        });

        if (! $readmes->hasResults()) {
            return null;
        }

        return $readmes->getIterator()->current();
    }
}
