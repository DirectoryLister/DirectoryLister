<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Actions\CanonicalPath;
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
        private CanonicalPath $canonicalPath,
    ) {}

    public function __invoke(Request $request, Response $response, ?string $path = null): ResponseInterface
    {
        $path = $this->stripEntryScript($request, $path);

        $queryParams = $request->getQueryParams();
        $firstQueryParam = array_key_first($queryParams);

        if (in_array($firstQueryParam, self::PROTECTED_PARAMS, true)) {
            $targetPath = ($this->canonicalPath)($queryParams[$firstQueryParam]);

            if ($targetPath !== null && ($this->isDirectLink)($targetPath)) {
                $requestPath = $path === null ? '' : (($this->canonicalPath)($path) ?? '');

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

    /**
     * Strip the entry script from the request path so it may be resolved from
     * the query string (e.g. /index.php?dir=foo).
     */
    private function stripEntryScript(Request $request, ?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $scriptName = basename($request->getServerParams()['SCRIPT_NAME'] ?? '');

        if ($scriptName !== '' && ($path === $scriptName || str_starts_with($path, $scriptName . '/'))) {
            return null;
        }

        return $path;
    }

    /** Determine if a path is within a protected path. */
    private function isWithin(string $path, string $protected): bool
    {
        if ($path === '' || $protected === '') {
            return false;
        }

        return $path === $protected || str_starts_with($path, $protected . '/');
    }
}
