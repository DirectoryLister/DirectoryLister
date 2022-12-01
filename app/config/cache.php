<?php

use App\Config;
use function DI\env;
use function DI\value;

return [
    /**
     * The application cache driver. Setting this value to 'array' will disable
     * the cache across requests. Additional driver-specific options may require
     * configuration below.
     *
     * Possible values: apcu, array, file, memcached, redis, php-file
     *
     * Default value: 'file'
     */
    'cache_driver' => env('CACHE_DRIVER', 'file'),

    /**
     * The app cache lifetime (in seconds). If set to 0, cache indefinitely.
     *
     * Default value: 3600 (one hour)
     */
    'cache_lifetime' => env('CACHE_LIFETIME', 3600),

    /**
     * Some cache drivers require manually pruning the cache periodically to
     * remove expired items. This is the percentage chance (out of 100) of a
     * request "winning" the lottery causing the cache to be pruned.
     *
     * Default value: 2
     */
    'cache_lottery' => env('CACHE_LOTTERY', 2),

    /**
     * Path to the view cache directory. Set to 'false' to disable
     * view caching entirely. The view cache is separate from the application
     * cache defined above.
     *
     * Default value: 'app/cache/views'
     */
    'view_cache' => env('VIEW_CACHE', 'app/cache/views'),

    /**
     * The Memcached server hostname or IP address.
     *
     * Default value: 'localhost'
     */
    'memcached_host' => env('MEMCACHED_HOST', 'localhost'),

    /**
     * The Memcached server port.
     *
     * Default value: 11211
     */
    'memcached_port' => env('MEMCACHED_PORT', 11211),

    /**
     * The Memcached configuration closure. This option is used when the
     * 'cache_driver' configuration option is set to 'memcached'. The closure
     * receives a Memcached object as it's only parameter. You can use this
     * object to configure the Memcached connection. At a minimum you must
     * connect to one or more Memcached servers via the 'addServer()' or
     * 'addServers()' methods.
     *
     * Reference the PHP Memcached documentation for Memcached configuration
     * options: https://secure.php.net/manual/en/book.memcached.php
     *
     * Default value: Connects to a server at localhost:11211
     */
    'memcached_config' => value(function (Memcached $memcached, Config $config): void {
        $memcached->addServer(
            $config->get('memcached_host'),
            $config->get('memcached_port')
        );
    }),

    /**
     * The Redis server hostname or IP address.
     *
     * Default value: 'localhost'
     */
    'redis_host' => env('REDIS_HOST', 'localhost'),

    /**
     * The Redis server port.
     *
     * Default value: 6379
     */
    'redis_port' => env('REDIS_PORT', 6379),

    /**
     * The Redis configuration closure. This option is used when the
     * 'cache_driver' configuration option is set to 'redis'. The closure
     * receives a Redis object as it's only parameter. You can use this object
     * to configure the Redis connection. At a minimum you must connect to one
     * or more Redis servers via the 'connect()' or 'pconnect()' methods.
     *
     * Reference the phpredis documentation for Redis configuration options:
     * https://github.com/phpredis/phpredis#readme
     *
     * Default value: Connects to a server at localhost:6379
     */
    'redis_config' => DI\value(function (Redis $redis, Config $config): void {
        $redis->pconnect(
            $config->get('redis_host'),
            $config->get('redis_port')
        );
    }),

    /**
     * HTTP cache values for controlling browser page cache durations.
     *
     * Possible values: An array of content types mapped to their cache duration
     * in seconds
     *
     * Default value: [
     *     'application/json' => '300',
     *     'application/zip' => '300',
     * ]
     */
    'http_cache' => [
        'application/json' => 300,
        'application/zip' => 300,
    ],

];
