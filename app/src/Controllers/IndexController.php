<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Actions\IsDirectLink;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController
{
    private const PROTECTED_PARAMS = ['dir', 'file', 'zip', 'info'];

    public function __construct(
        private Container $container,
        private Twig $view,
        private TranslatorInterface $translator,
        private IsDirectLink $isDirectLink,
    ) {}

    public function __invoke(Request $request, Response $response, ?string $path = null): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $firstQueryParam = array_key_first($queryParams);

        if (in_array($firstQueryParam, self::PROTECTED_PARAMS)) {
            $target = $queryParams[$firstQueryParam];
            $fullPath = $this->container->call('full_path', ['path' => $target]);

            if (($this->isDirectLink)($fullPath)) {
                $requestPath = $path === null ? '' : mb_rtrim($path, '/');
                $targetPath = mb_rtrim($target, '/');

                if (! $this->isWithin($requestPath, $targetPath) && ! $this->isWithin($targetPath, $requestPath)) {
                    return $this->view->render($response->withStatus(404), 'error.twig', [
                        'message' => $this->translator->trans('error.file_not_found'),
                    ]);
                }
            }
        }

        $controller = match ($firstQueryParam) {
            'file' => FileController::class,
            'info' => FileInfoController::class,
            'search' => SearchController::class,
            'zip' => ZipController::class,
            default => DirectoryController::class,
        };

        return $this->container->call($controller, [$request, $response, $path]);
    }

    /** Determine if a path is within a protected path. */
    private function isWithin(string $path, string $protected): bool
    {
        return $path === $protected || str_starts_with($path, $protected . '/');
    }
}
