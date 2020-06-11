<?php

namespace Tests\Factories;

use App\Exceptions\InvalidConfiguration;
use App\Factories\CacheFactory;
use Symfony\Component\Cache\Adapter;
use Tests\TestCase;

/** @covers \App\Factories\CacheFactory */
class CacheFactoryTest extends TestCase
{
    /** @dataProvider cacheAdapters */
    public function test_it_can_compose_an_adapter(string $config, string $adapter): void
    {
        $this->container->set('cache_driver', $config);

        $cache = (new CacheFactory($this->container))();

        $this->assertInstanceOf($adapter, $cache);
    }

    public function test_it_throws_a_runtime_exception_with_an_invalid_sort_order(): void
    {
        $this->container->set('cache_driver', 'invalid');

        $this->expectException(InvalidConfiguration::class);

        (new CacheFactory($this->container))();
    }

    public function cacheAdapters(): array
    {
        return [
            'APCu adapter' => ['apcu', Adapter\ApcuAdapter::class],
            'Array adapter' => ['array', Adapter\ArrayAdapter::class],
            'File adapter' => ['file', Adapter\FilesystemAdapter::class],
            'Memcached adapter' => ['memcached', Adapter\MemcachedAdapter::class],
            'PHP files adapter' => ['php-file', Adapter\PhpFilesAdapter::class],
            'Redis adapter' => ['redis', Adapter\RedisAdapter::class],
        ];
    }
}
