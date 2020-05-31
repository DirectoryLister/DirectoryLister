<?php

namespace App\Factories;

use App\Exceptions\InvalidConfiguration;
use DI\Container;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class CacheFactory
{
    /** @const Namespace for external cache drivers */
    protected const NAMESPACE_EXTERNAL = 'directory_lister';

    /** @const Namespace for internal cache drivers */
    protected const NAMESPACE_INTERNAL = 'app';

    /** @var Container The application container */
    protected $container;

    /**
     * Create a new CacheFactory object.
     *
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Initialize and return a CacheInterface.
     *
     * @return \Symfony\Contracts\Cache\CacheInterface
     */
    public function __invoke(): CacheInterface
    {
        switch ($this->container->get('cache_driver')) {
            case 'apcu':
                return new ApcuAdapter(
                    self::NAMESPACE_EXTERNAL,
                    $this->container->get('cache_lifetime')
                );

            case 'array':
                return new ArrayAdapter($this->container->get('cache_lifetime'));

            case 'file':
                return new FilesystemAdapter(
                    self::NAMESPACE_INTERNAL,
                    $this->container->get('cache_lifetime'),
                    $this->container->get('cache_path')
                );

            case 'memcached':
                $this->container->call('memcached_config', [$memcached = new \Memcached]);

                return new MemcachedAdapter(
                    $memcached,
                    self::NAMESPACE_EXTERNAL,
                    $this->container->get('cache_lifetime')
                );

            case 'php-file':
                return new PhpFilesAdapter(
                    self::NAMESPACE_INTERNAL,
                    $this->container->get('cache_lifetime'),
                    $this->container->get('cache_path')
                );

            case 'redis':
                $this->container->call('redis_config', [$redis = new \Redis]);

                return new RedisAdapter(
                    $redis,
                    self::NAMESPACE_EXTERNAL,
                    $this->container->get('cache_lifetime')
                );

            default:
                throw InvalidConfiguration::fromConfig('cache_driver', $this->container->get('cache_driver'));
        }
    }
}
