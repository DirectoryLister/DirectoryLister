<?php

namespace Tests\Middlewares;

use App\Middlewares\PruneCacheMiddleware;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Cache\Adapter;
use Symfony\Contracts\Cache\CacheInterface;
use Tests\TestCase;

/** @covers \App\Middlewares\PruneCacheMiddleware */
class PruneCacheMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->container->set('cache_lottery', 100);
    }

    /** @dataProvider pruneableCacheAdapters */
    public function test_it_prunes_the_cache_whe_using_a_pruneable_adapter_and_winning_the_lottery(string $cacheAdapter): void
    {
        /** @var CacheInterface&MockObject */
        $cache = $this->createMock($cacheAdapter);
        $cache->expects($this->once())->method('prune');

        (new PruneCacheMiddleware($this->config, $cache))(
            $this->createMock(ServerRequestInterface::class),
            $this->createMock(RequestHandlerInterface::class)
        );
    }

    /** @dataProvider nonPruneableCacheAdapters */
    public function test_it_does_not_prune_the_cache_when_using_a_non_prunable_adapter(string $cacheAdapter): void
    {
        /** @var CacheInterface&MockObject */
        $cache = $this->getMockBuilder($cacheAdapter)
            ->disableOriginalConstructor()
            ->addMethods(['prune'])
            ->getMock();

        $cache->expects($this->never())->method('prune');

        (new PruneCacheMiddleware($this->config, $cache))(
            $this->createMock(ServerRequestInterface::class),
            $this->createMock(RequestHandlerInterface::class)
        );
    }

    public function pruneableCacheAdapters(): array
    {
        return [
            [Adapter\FilesystemAdapter::class],
            [Adapter\PhpFilesAdapter::class],
        ];
    }

    public function nonPruneableCacheAdapters(): array
    {
        return [
            [Adapter\ApcuAdapter::class],
            [Adapter\ArrayAdapter::class],
            [Adapter\MemcachedAdapter::class],
            [Adapter\RedisAdapter::class],
        ];
    }
}
