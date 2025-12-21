<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Actions\IsHidden;
use DI\Attribute\Inject;
use DI\Container;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use SplFileInfo;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FileInfoController
{
    #[Inject('max_hash_size')]
    private int $maxHashSize;

    public function __construct(
        private Container $container,
        private CacheInterface $cache,
        private IsHidden $isHidden,
        private TranslatorInterface $translator
    ) {}

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $this->container->call('full_path', ['path' => $request->getQueryParams()['info']]);

        try {
            $file = new SplFileInfo((string) realpath($path));
        } catch (Exception) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        if (! $file->isFile() || $this->isHidden->file($file)) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        if ($file->getSize() >= $this->maxHashSize) {
            return $response->withStatus(500, $this->translator->trans('error.file_size_exceeded'));
        }

        $response->getBody()->write($this->cache->get(
            sprintf('file-info-%s', sha1((string) $file->getRealPath())),
            fn (): string => (string) json_encode(['hashes' => $this->calculateHashes($file)], flags: JSON_THROW_ON_ERROR)
        ));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Get an array of hashes for a file.
     *
     * @return array{md5: string, sha1: string, sha256: string}
     */
    private function calculateHashes(SplFileInfo $file): array
    {
        return [
            'md5' => (string) hash_file('md5', (string) $file->getRealPath()),
            'sha1' => (string) hash_file('sha1', (string) $file->getRealPath()),
            'sha256' => (string) hash_file('sha256', (string) $file->getRealPath()),
        ];
    }
}
