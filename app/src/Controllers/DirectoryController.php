<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config;
use DI\Container;
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
    public function __construct(
        private Container $container,
        private Config $config,
        private Finder $finder,
        private Twig $view,
        private TranslatorInterface $translator
    ) {}

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $relativePath = $request->getQueryParams()['dir'] ?? '.';
        $fullPath = $this->container->call('full_path', ['path' => $relativePath]);

        try {
            $files = $this->finder->in($fullPath)->depth(0);
        } catch (Exception) {
            return $this->view->render($response->withStatus(404), 'error.twig', [
                'message' => $this->translator->trans('error.directory_not_found'),
            ]);
        }

        return $this->view->render($response, 'index.twig', [
            'files' => $files,
            'path' => $relativePath,
            'readme' => $this->readme($files),
            'title' => $relativePath == '.' ? 'Home' : $relativePath,
        ]);
    }

    /** Return the README file within a finder object. */
    private function readme(Finder $files): ?SplFileInfo
    {
        if (! $this->config->get('display_readmes')) {
            return null;
        }

        $readmes = (clone $files)->name('/^README(?:\..+)?$/i');

        $readmes->filter(
            static fn (SplFileInfo $file): bool => (bool) preg_match('/text\/.+/', (string) mime_content_type($file->getPathname()))
        )->sort(
            static fn (SplFileInfo $file1, SplFileInfo $file2): int => $file1->getExtension() <=> $file2->getExtension()
        );

        if (! $readmes->hasResults()) {
            return null;
        }

        return $readmes->getIterator()->current();
    }
}
