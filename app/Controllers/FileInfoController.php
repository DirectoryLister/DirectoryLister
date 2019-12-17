<?php

namespace App\Controllers;

use RuntimeException;
use Slim\Psr7\Response;
use SplFileInfo;

class FileInfoController
{
    /**
     * Invoke the FileInfoController.
     *
     * @param \App\Http\Response $response
     * @param string             $path
     */
    public function __invoke(Response $response, string $path = '.')
    {
        if (! is_file($path)) {
            throw new RuntimeException('Invalid file path', $path);
        }

        $file = new SplFileInfo($path);

        $response->getBody()->write(json_encode([
            'hashes' => [
                'md5' => hash('md5', file_get_contents($file->getPathname())),
                'sha1' => hash('sha1', file_get_contents($file->getPathname())),
                'sha256' => hash('sha256', file_get_contents($file->getPathname())),
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
