# Configuration Reference

## Bootstrap Configuration

These configuration options are special configuration options only configurable through environment variables (e.g. through the `.env` file).

::: info
There are no corresponding configuration options for these values in the `app/config` definitions because they are applied _before_ the application container (and configuration) is loaded.
:::

### `FILES_PATH`

A path (relative or absolute) to the directory in which you would like to list. This may even be a path outside of the application directory. When left unset, the root application directory (i.e. the folder where `index.php` is located) will be listed.

:::tabs
== Possible Values
An absolute or relative file path (string)
== Default Value
`<unset>`
:::

### `COMPILE_CONTAINER`

Whether or not the application container will be compiled. When set to `false` the container will _not_ be compiled and cached. If left unset the container will be compiled and cached on first run and the cached container will be reused on subsequent requests. Disabling this comes with a (likely negligible) performance hit. Generally this should not be changed but might be necessary to modify when running Directory Lister on a read-only filesystem.

:::tabs
== Possible Values
`false` or `<unset>`
== Default Value
`<unset>`
:::

## Runtime Configuration

General application runtime configuration can be controlled through environment variables. This can be accomplished via an `.env` file located in the root of your application. An example file is provided as `.env.example`.

::: info
Advanced configuration can be accomplished via the app config located at `app/config/app.php`. However, changes to this fill will need to be manually re-applied between upgrades. It is highly recommended to stick to environment variables for controlling app configuration unless absolutely necessary.
:::

### `APP_DEBUG`

Enable application debugging and display error messages.

::: danger
It is recommended that debug remains OFF unless troubleshooting an issue. Leaving this enabled WILL cause leakage of sensitive server information.
:::

:::tabs
== Possible Values
`true` or `false`
== Default Value
`false`
:::

### `APP_LANGUAGE`

The application's interface language.

:::tabs
== Possible Values
See the [`app/translations`](https://github.com/DirectoryLister/DirectoryLister/tree/master/app/translations) folder for available translations.
== Default Value
`en` (English)
:::

### `DATE_FORMAT`

The format used for rendering dates in the application views.

:::tabs
== Possible Values
See the [PHP `date` format documentation](https://www.php.net/manual/en/function.date.php#refsect1-function.date-parameters) for possible values.
== Default Value
`Y-m-d H:i:s`
:::

### `DIRECT_LINKS`

Comma separated list of [file matching patterns](./file-matching-patterns.md) to be directly linked. Directly linked files will not be served by Directory Lister but handled by the web server directly.

::: warning
This setting has no effect when [`FILES_PATH`](#files_path) is set or when used in the Docker container.
:::

##### Example

To directly link all `.htm`, `.html` _one or more folders deep_ and `.php` files _in all folders and sub-folders_.

```
DIRECT_LINKS=**/index.{htm,html},**.php
```

:::tabs
== Possible Values
A comma separated list of [file matching patterns](./file-matching-patterns.md).
== Default Value
`null`
:::

### `DISPLAY_READMES`

Parse and render `README` files on the page.

:::tabs
== Possible Values
`true` or `false`
== Default Value
`true`
:::

### `HIDDEN_FILES_LIST`

File containing hidden file definitions. Will be merged with definitions from the 'hidden\_files' configuration option.

::: info
See the [Hiding Files](./#hiding-files) page for additional info on hiding files.
:::

:::tabs
== Possible Values
A path (string) to a file
== Default Value
`.hidden`
:::

### `HIDE_APP_FILES`

Hide application specific files/directories (i.e. `index.php` and the `app` folder).

:::tabs
== Possible Values
`true` or `false`
== Default Value
`true`
:::

### `HIDE_DOT_FILES`

Hide dot files/directories from the listing.

:::tabs
== Possible Values
`true` or `false`
== Default Value
`true`
:::

### `HIDE_VCS_FILES`

Hide the files Version Control Systems (i.e. Git and Mercurial) use to store their metadata.

:::tabs
== Possible Values
`true` or `false`
== Default Value
`true`
:::

### `HOME_TEXT`

Text of the `home` link in the navigation breadcrumbs. If undefined or `null` will use the translated form of "home" from your selected language.

:::tabs
== Possible Values
Any string
== Default Value
`null`
:::

### `MAX_HASH_SIZE`

The maximum file size (in bytes) that can be hashed. This helps to prevent timeouts for excessively large files.

::: warning
The larger a file is the longer it will take to calculate hashes for that file.
:::

:::tabs
== Possible Values
Any positive integer `0` - `9223372036854775807` ([`PHP_INT_MAX`](https://www.php.net/manual/en/reserved.constants.php#constant.php-int-max))
== Default Value
`1000000000` (1 GB)
:::

### `META_DESCRIPTION`

Meta tag description (i.e. `<meta name="description">`) text.

:::tabs
== Possible Values
Any string
== Default Value
`Yet another directory listing, powered by Directory Lister.`
:::

### `READMES_FIRST`

Show READMEs before the file listing.

:::tabs
== Possible Values
`true` or `false`
== Default Value
`false`
:::

### `REVERSE_SORT`

When enabled, reverses the order of files (after sorting is applied).

:::tabs
== Possible Values
`true` or `false`
== Default Value
`false`
:::

### `SITE_TITLE`

The title of your directory listing. This will be displayed in the browser tab/title bar along with the current path.

:::tabs
== Possible Values
Any string
== Default Value
`Directory Lister`
:::

### `SORT_ORDER`

Sorting order of files and folders. Can be one of several predefined values. Advanced sorting configuration can be achieved by using an anonymous function. See the [`sort_order` configuration option](./advanced-configuration.md#sort_order) documentation for more information.

:::tabs
== Possible Values
`type`, `natural`, `name`, `accessed`, `changed`, `modified`, `<anonymous function>`
== Default Value
`type`
:::

### `TIMEZONE`

Time zone used for date formatting.

:::tabs
== Possible Values
For a list of supported time zones see: [https://www.php.net/manual/en/timezones.php](https://www.php.net/manual/en/timezones.php).
== Default Value
The server's timezone
:::

### `ZIP_DOWNLOADS`

Enable downloading of directories as a zip archive.

:::tabs
== Possible Values
`true` or `false`
== Default Value
`true`
:::

### `ZIP_COMPRESS`

Compress Zip using Deflate. Enabling this option prevents file size estimation and it may prevent zip download resuming when paused.

:::tabs
== Possible Values
`true` or `false`
== Default Value
`false`
:::

## Cache Configuration

Application cache configuration is also controlled through environment variables. This can be accomplished via an `.env` file located in the root of your application. An example file is provided as `.env.example`.

::: info
Advanced configuration can be accomplished via the cache config located at `app/config/cache.php`. However, changes to this fill will need to be manually re-applied between upgrades. It is highly recommended to stick to environment variables for controlling cache configuration unless absolutely necessary.
:::

### `CACHE_DRIVER`

The application cache driver. Setting this value to `array` will disable the cache across requests. Additional driver-specific options may be required with certain values.

:::tabs
== Possible Values
`apcu`, `array`, `file`, `memcached`, `redis`, `php-file`, `valkey`
== Default Value
`file`
:::

### `CACHE_LIFETIME`

The app cache lifetime (in seconds). Setting this value to `0` will cache indefinitely.

:::tabs
== Possible Values
Any positive integer
== Default Value
`60` (one hour)
:::

### `CACHE_LOTTERY`

Some cache drivers require manually pruning the cache periodically to remove expired items. This is the percentage chance (out of 100) of a request "winning" the lottery causing the cache to be pruned.

:::tabs
== Possible Values
Any integer betweeen `1` and `100`
== Default Value
`2`
:::

### `MEMCACHED_HOST`

The Memcached server hostname or IP address.

:::tabs
== Possible Values
Any string
== Default Value
`localhost`
:::

::: info
Advanced Memcached configuration is possible via the [`memcached_config` option](./advanced-configuration.md#memcached_config) in `app/config/cache.php`
:::

### `MEMCACHED_PORT`

The Memcached server port.

:::tabs
== Possible Values
Any valid port as an integer (`0` to `65353`)
== Default Value
`11211`
:::

::: info
Advanced Memcached configuration is possible via the [`memcached_config` option](./advanced-configuration.md#memcached_config) in `app/config/cache.php`
:::

### `REDIS_HOST`

The Redis server hostname or IP address.

:::tabs
== Possible Values
Any string
== Default Value
`localhost`
:::

::: info
Advanced Reds configuration is possible via the [`redis_config` option](./advanced-configuration.md#redis_config) in `app/config/cache.php`
:::

### `REDIS_PORT`

The Redis server port.

:::tabs
== Possible Values
Any valid port as an integer (`0` to `65353`)
== Default Value
`6379`
:::

::: info
Advanced Reds configuration is possible via the [`redis_config` option](./advanced-configuration.md#redis_config) in `app/config/cache.php`
:::

### `VIEW_CACHE`

Path to the view cache directory. Set to `false` to disable view caching entirely.

:::tabs
== Possible Values
A directory path as a string or `false` to disable the view cache entirely
== Default Value
`app/cache/views`
:::

## Icon Configuration

The icon config is located at `app/config/icons.php`. Here is were file types are mapped to their respective icons. The mapping is a PHP array where the array key is the file extension (without a preceding dot) and the array value is the desired [Font Awesome](https://fontawesome.com/icons) class names.

::: code-group

```php [icons.php]
return [
    'icons' => [
        '7z' => 'fas fa-file-archive',
        'aac' => 'fas fa-music',
        'accdb' => 'fas fa-database',
        'ai' => 'fas fa-image',
        'aif' => 'fas fa-music',
        'apk' => 'fab fa-android',
        'app' => 'fas fa-window',
        'avi' => 'fas fa-video',
        'bak' => 'fas fa-save',
        'bat' => 'fas fa-terminal',
        // etc...
    ],
];
```

:::
