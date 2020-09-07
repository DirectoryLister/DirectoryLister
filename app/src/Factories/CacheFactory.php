<?php

namespace App\Factories;

use App\Config;
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

    /** @var Config The application configuration */
    protected $config;

    /** Create a new CacheFactory object. */
    public function __construct(Container $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /** Initialize and return a CacheInterface. */
    public function __invoke(): CacheInterface
    {
        switch ($this->config->get('cache_driver')) {
            case 'apcu':
                return new ApcuAdapter(
                    self::NAMESPACE_EXTERNAL,
                    $this->config->get('cache_lifetime')
                );

            case 'array':
                return new ArrayAdapter($this->config->get('cache_lifetime'));

            case 'file':
                return new FilesystemAdapter(
                    self::NAMESPACE_INTERNAL,
                    $this->config->get('cache_lifetime'),
                    $this->config->get('cache_path')
                );

            case 'memcached':
                $this->container->call('memcached_config', [$memcached = new \Memcached]);

                return new MemcachedAdapter(
                    $memcached,
                    self::NAMESPACE_EXTERNAL,
                    $this->config->get('cache_lifetime')
                );

            case 'php-file':
                return new PhpFilesAdapter(
                    self::NAMESPACE_INTERNAL,
                    $this->config->get('cache_lifetime'),
                    $this->config->get('cache_path')
                );

            case 'redis':
                $this->container->call('redis_config', [$redis = new \Redis]);

                return new RedisAdapter(
                    $redis,
                    self::NAMESPACE_EXTERNAL,
                    $this->config->get('cache_lifetime')
                );

            default:
                throw InvalidConfiguration::fromConfig('cache_driver', $this->config->get('cache_driver'));
        }
    }
}
