<?php

use App\Support\Helpers;

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
    'cache_driver' => Helpers::env('CACHE_DRIVER', 'file'),

    /**
     * The app cache lifetime (in seconds). If set to 0, cache indefinitely.
     *
     * Default value: 0 (indefinitely)
     */
    'cache_lifetime' => Helpers::env('CACHE_LIFETIME', 0),

    /**
     * Path to the view cache directory. Set to 'false' to disable
     * view caching entirely. The view cache is separate from the application
     * cache defined above.
     *
     * Default value: 'app/cache/views'
     */
    'view_cache' => Helpers::env('VIEW_CACHE', 'app/cache/views'),

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
     * Default value: Adds a server at localhost:11211
     */
    'memcached_config' => DI\value(function (Memcached $memcached): void {
        $memcached->addServer(
            Helpers::env('MEMCACHED_HOST', 'localhost'),
            Helpers::env('MEMCACHED_PORT', 11211)
        );
    }),

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
     * Default value: Adds a server at localhost:6379
     */
    'redis_config' => DI\value(function (Redis $redis): void {
        $redis->pconnect(
            Helpers::env('REDIS_HOST', 'localhost'),
            Helpers::env('REDIS_PORT', 6379)
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
