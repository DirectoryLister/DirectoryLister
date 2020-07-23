<?php

use App\Config;

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
    'cache_driver' => DI\env('CACHE_DRIVER', 'file'),

    /**
     * The app cache lifetime (in seconds). If set to 0, cache indefinitely.
     *
     * Default value: 0 (indefinitely)
     */
    'cache_lifetime' => DI\env('CACHE_LIFETIME', 0),

    /**
     * Path to the view cache directory. Set to 'false' to disable
     * view caching entirely. The view cache is separate from the application
     * cache defined above.
     *
     * Default value: 'app/cache/views'
     */
    'view_cache' => DI\env('VIEW_CACHE', 'app/cache/views'),

    /**
     * The Memcached server hostname or IP address.
     *
     * Default value: 'localhost'
     */
    'memcached_host' => DI\env('MEMCACHED_HOST', 'localhost'),

    /**
     * The Memcached server port.
     *
     * Default value: 11211
     */
    'memcached_port' => DI\env('MEMCACHED_PORT', 11211),

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
    'memcached_config' => DI\value(function (Memcached $memcached, Config $config): void {
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
    'redis_host' => DI\env('REDIS_HOST', 'localhost'),

    /**
     * The Redis server port.
     *
     * Default value: 6379
     */
    'redis_port' => DI\env('REDIS_PORT', 6379),

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
     * HTTP expires values to control browser cache durations.
     *
     * Possible values: An array of mime types mapped to their cache duration
     * as a relative datetime string.
     *
     * Default value: [
     *     'application/zip' => '+1 hour',
     *     'text/json' => '+1 hour',
     * ]
     */
    'http_expires' => [
        'application/zip' => '+1 hour',
        'text/json' => '+1 hour',
    ],
];
