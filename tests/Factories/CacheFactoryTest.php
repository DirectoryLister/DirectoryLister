<?php

namespace Tests\Factories;

use App\Exceptions\InvalidConfiguration;
use App\Factories\CacheFactory;
use Symfony\Component\Cache\Adapter;
use Tests\TestCase;

class CacheFactoryTest extends TestCase
{
    public function test_it_can_compose_an_apcu_adapter(): void
    {
        $this->container->set('cache_driver', 'apcu');

        $cache = (new CacheFactory($this->container))();

        $this->assertInstanceOf(Adapter\ApcuAdapter::class, $cache);
    }

    public function test_it_can_compose_an_array_adapter(): void
    {
        $this->container->set('cache_driver', 'array');

        $cache = (new CacheFactory($this->container))();

        $this->assertInstanceOf(Adapter\ArrayAdapter::class, $cache);
    }

    public function test_it_can_compose_a_filesystem_adapter(): void
    {
        $this->container->set('cache_driver', 'file');

        $cache = (new CacheFactory($this->container))();

        $this->assertInstanceOf(Adapter\FilesystemAdapter::class, $cache);
    }

    public function test_it_can_compose_a_memcached_adapter(): void
    {
        $this->container->set('cache_driver', 'memcached');

        $cache = (new CacheFactory($this->container))();

        $this->assertInstanceOf(Adapter\MemcachedAdapter::class, $cache);
    }

    public function test_it_can_compose_a_php_files_adapter(): void
    {
        $this->container->set('cache_driver', 'php-file');

        $cache = (new CacheFactory($this->container))();

        $this->assertInstanceOf(Adapter\PhpFilesAdapter::class, $cache);
    }

    public function test_it_can_compose_a_redis_adapter(): void
    {
        $this->container->set('cache_driver', 'redis');

        $cache = (new CacheFactory($this->container))();

        $this->assertInstanceOf(Adapter\RedisAdapter::class, $cache);
    }

    public function test_it_throws_a_runtime_exception_with_an_invalid_sort_order(): void
    {
        $this->container->set('cache_driver', 'invalid');

        $this->expectException(InvalidConfiguration::class);

        (new CacheFactory($this->container))();
    }
}
