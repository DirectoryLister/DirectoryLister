<?php

namespace Tests\Factories;

use App\Exceptions\InvalidConfiguration;
use App\Factories\CacheFactory;
use RedisException;
use Symfony\Component\Cache\Adapter;
use Tests\TestCase;

/** @covers \App\Factories\CacheFactory */
class CacheFactoryTest extends TestCase
{
    /** @dataProvider cacheAdapters */
    public function test_it_can_compose_an_adapter(string $config, string $adapter, bool $available = true): void
    {
        if (! $available) {
            $this->markTestSkipped('Cache driver unavailable');
        }

        $this->container->set('cache_driver', $config);

        try {
            $cache = (new CacheFactory($this->container, $this->config))();
        } catch (RedisException $exception) {
            $this->markTestSkipped(sprintf('Redis: %s', $exception->getMessage()));
        }

        $this->assertInstanceOf($adapter, $cache);
    }

    public function test_it_throws_a_runtime_exception_with_an_invalid_sort_order(): void
    {
        $this->container->set('cache_driver', 'invalid');

        $this->expectException(InvalidConfiguration::class);

        (new CacheFactory($this->container, $this->config))();
    }

    public function cacheAdapters(): array
    {
        return [
            'APCu adapter' => ['apcu', Adapter\ApcuAdapter::class, extension_loaded('apcu') && ini_get('apc.enabled')],
            'Array adapter' => ['array', Adapter\ArrayAdapter::class, true],
            'File adapter' => ['file', Adapter\FilesystemAdapter::class, true],
            'Memcached adapter' => ['memcached', Adapter\MemcachedAdapter::class, class_exists('Memcached')],
            'PHP files adapter' => ['php-file', Adapter\PhpFilesAdapter::class, true],
            'Redis adapter' => ['redis', Adapter\RedisAdapter::class, class_exists('Redis')],
        ];
    }
}
