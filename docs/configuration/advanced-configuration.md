# Advanced Configuration

Some configuration values do not have a corresponding variable. These values can only be controlled through their entries in the `app/config` files.

## `hidden_files`

Array of hidden file definitions. Will be merged with definitions in the file defined in the `hidden_files_list` configuration option. Supports glob patterns (e.g. `*.txt`, `file.{yml,yaml}`, etc.).

::: info
See the [Hiding Files](./#hiding-files) page for additional info on hiding files.
:::

**Possible Values:** An array of paths (strings)

**Default Value:** `[]` (an empty array)

**Example:**

```php
'hidden_files' => [
    'somefile.txt', // Matches 'somefile.txt' exactly
    'README.*', // Matches files named 'README' with any file extension
    'foo/*', // Matches all files in the 'foo' directory
    'schema.{ya?ml}', // Matches 'schema.yml' or 'schema.yaml'
]
```

## `memcached_config`

The Memcached configuration [anonymous function](https://www.php.net/manual/en/functions.anonymous.php) (closure). This option is used when the `cache_driver` configuration option is set to `memcached`. The closure receives a `Memcached` object as it's only parameter. You can use this object to configure the Memcached connection. At a minimum you must connect to one or more Memcached servers via the `addServer()` or `addServers()` methods.

::: info
Reference the [PHP Memcached documentation](https://secure.php.net/manual/en/book.memcached.php) for Memcached configuration options.
:::

**Possible Values:**

An anonymous function that receives a `Memcached` object

```php
function (Memcached $memcached): void {
    // Configure the $memcached object
}
```

**Default Value:**

```php
DI\value(function (Memcached $memcached, Config $config): void {
    $memcached->addServer(
        $config->get('memcached_host'),
        $config->get('memcached_port')
    );
})
```

This closure adds a single connection to a server at the host defined by [`memcached_host`](./configuration-reference.md#memcached_host) on the port defined by [`memcached_port`](./configuration-reference.md#memcached_port).

**Environment Variables:** Uses the `MEMCACHED_HOST` and `MEMCACHED_PORT` variables by default

## `redis_config`

The Redis configuration [anonymous function](https://www.php.net/manual/en/functions.anonymous.php) (closure). This option is used when the `cache_driver` configuration option is set to `redis`. The closure receives a `Redis` object as it's only parameter. You can use this object to configure the Redis connection. At a minimum you must connect to one or more Redis servers via the `connect()` or `pconnect()` methods.

::: info
Reference the [phpredis documentation](https://github.com/phpredis/phpredis#readme) for Redis configuration options.
:::

**Possible Values:**

An anonymous function that receives a `Redis` object

```php
function (Redis $redis): void {
    // Configure the $redis object
}
```

**Default Value:**

```php
DI\value(function (Redis $redis, Config $config): void {
    $redis->pconnect(
        $config->get('redis_host'),
        $config->get('redis_port')
    );
})
```

This closure adds a single connection to a server at the host defined by [`redis_host`](./configuration-reference.md#redis_host) on the port defined by [`redis_port`](./configuration-reference.md#redis_port).

**Environment Variables:** Uses the `REDIS_HOST` and `REDIS_PORT` variables by default

## `http_cache`

HTTP cache values for controlling browser page cache duration. An array of mime types mapped to their cache duration in seconds.

**Possible Values:**

An array of [mime types](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types) mapped to their cache duration as a seconds (integers).

**Default Value:**

```php
[
    'application/json' => 300,
    'application/zip' => 300,
]
```

## `sort_order`

An [anonymous function](https://www.php.net/manual/en/functions.anonymous.php) can be used to customize the sort order of files and folders in your directory listing. The anonymous function receives two `SplFileInfo` objects as arguments and must return an integer less than, equal to, or greater than zero to represent the first argument being respectively less than, equal to, or greater than the second.

```php
'sort_order' => \DI\value(
    function (SplFileInfo $file1, SplFileInfo $file2): int {
        return strcmp($file1->getRealPath(), $file2->getRealPath());
    })
);
```

::: warning
The anonymous function must be wrapped in a `\DI\value()` function.
:::
