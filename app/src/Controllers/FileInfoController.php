<?php

namespace App\Controllers;

use App\Config;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use SplFileInfo;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Support\Utils;

class FileInfoController
{
    /** @var Config The application configuration */
    protected $config;

    /** @var CacheInterface The application cache */
    protected $cache;

    /** @var TranslatorInterface Translator component */
    protected $translator;

    /** Create a new FileInfoHandler object. */
    public function __construct(
        Config $config,
        CacheInterface $cache,
        TranslatorInterface $translator
    ) {
        $this->config = $config;
        $this->cache = $cache;
        $this->translator = $translator;
    }

    /** Invoke the FileInfoHandler. */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $request->getQueryParams()['info'];

        $file = new SplFileInfo(
            realpath($this->config->get('base_path') . $path)
        );

        if (! $file->isFile()) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        $response->getBody()->write($this->cache->get(
            sprintf('file-info-%s', sha1($file->getRealPath())),
            function () use ($file): string {
                return json_encode(
                    ['modification time' => date($this->config->get('date_format'), $file->getMTime())] +
                    ['size' => Utils::sizeForHumans($file)] +
                    $this->calculateHashes($file)
                );
            }
        ));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /** Get an array of hashes for a file. */
    protected function calculateHashes(SplFileInfo $file): array
    {
        $maxHashSize = (int) $this->config->get('max_hash_size');

        if ($maxHashSize==0 || $file->getSize() >= $maxHashSize) {
            return [];
        }

        $supportedHashes = explode(',', $this->config->get('supported_hashes'));
        $calculatedHashes = [];

        foreach ($supportedHashes as &$hash) {
            $hash = trim($hash);
            $calculatedHashes[$hash] = hash_file($hash, $file->getRealPath());
        }

        return $calculatedHashes;
    }
}
