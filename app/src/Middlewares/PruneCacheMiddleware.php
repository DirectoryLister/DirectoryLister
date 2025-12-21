<?php

declare(strict_types=1);

namespace App\Middlewares;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Symfony\Component\Cache\PruneableInterface;
use Symfony\Contracts\Cache\CacheInterface;

class PruneCacheMiddleware
{
    #[Inject('cache_lottery')]
    private int $cacheLottery;

    public function __construct(
        private CacheInterface $cache
    ) {}

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (! $this->cache instanceof PruneableInterface) {
            return $response;
        }

        if ($this->winsLottery()) {
            $this->cache->prune();
        }

        return $response;
    }

    /** Determine if this request wins the lottery. */
    private function winsLottery(): bool
    {
        return random_int(1, 100) <= $this->cacheLottery;
    }
}
