<?php

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use SplFileInfo;
use Symfony\Contracts\Translation\TranslatorInterface;

class FileController
{
    public function __construct(
        private Container $container,
        private TranslatorInterface $translator
    ) {}

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $this->container->call('full_path', ['path' => $request->getQueryParams()['file']]);

        $file = new SplFileInfo((string) realpath($path));

        if (! $file->isFile()) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        $response = $response->withHeader('Content-Description', 'File Transfer');
        $response = $response->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $file->getFilename()));

        if ($file->getSize() !== false) {
            $response = $response->withHeader('Content-Length', (string) $file->getSize());
        }

        if ($file->getType() !== false) {
            $response = $response->withHeader('Content-Type', (string) $file->getType());
        }

        return $response->withBody(
            (new StreamFactory)->createStreamFromFile($file->getRealPath())
        );
    }
}
