# Development Environment

## Requirements

* [PHP](https://php.net) >= 8.2 with the `zip`, `dom` and `fileinfo` (and optionally `apcu`, `memcached`, `redis`) extensions
  * [Composer](https://getcomposer.org) for PHP dependency management
* [NPM](https://www.npmjs.com) for front end asset serving and bundling
* [Docker](https://www.docker.com) and [Docker Compose](https://docs.docker.com/compose/) for running the local development container
* [Git](https://git-scm.com/) for version control

## Instructions

::: info
These instructions are for setting up a local DEVELOPMENT environment. If you are looking for basic installation instruction see the [Installation](../installation.md) page instead.
:::

1. [Fork the Directory Lister repository to your own account](https://github.com/DirectoryLister/DirectoryLister/fork) (optional)
2. [Clone Directory Lister to a local repository](https://help.github.com/en/github/creating-cloning-and-archiving-repositories/cloning-a-repository)

    ```bash
    git clone {{ REPOSITORY_URL }}
    ```

3. Switch to the Directory Lister directory

    ```bash
    cd /path/to/DirectoryLister
    ```

4. Install and build PHP and JavaScript dependencies

    ```bash
    composer install
    npm install
    npm run dev
    ```

5. Run the local Docker container

    ```bash
    docker-compose up -d
    ```

6. Add a host name entry to `/etc/hosts` (optional)

    ```bash
    127.0.0.1  directory-lister.local
    ```

You should now be able to access your local Directory Lister installation at `http://localhost` (or [http://directory-lister.local](http://directory-lister.local) if you added a host name entry)

## Common Development Commands

Many common development actions have been defined in the `Makefile` and can be run with `make` command.

### Clear the application cache

::: code-group

```sh [Make]
make clear-cache
```

```sh [Manual]
rm --recursive --force app/cache/*
```

:::

### Build dependencies and assets (for production)

::: code-group

```sh [Make]
make production
```

```sh [Manual]
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
npm install --no-save
npm run build
npm prune --production
```

:::

### Clear built assets

::: code-group

```sh [Make]
make clear-assets
```

```sh [Manual]
rm --recursive --force app/assets/*
```

:::

### Run test suite

::: code-group

```sh [Make]
make tests
```

```sh [Composer]
composer exec phpunit
```

```sh [Manual]
app/vendor/bin/phpunit
```

:::

### Check or fix coding standards

**Make**

```sh
make coding-standards
```

::: info
This will apply coding standard fixes will be automatically.

See the Compor or Manual tab to report coding standard problems _without_ modifying files.
:::

**Composer**

```sh
composer exec php-cs-fixer fix [--diff] [--dry-run]
```

::: info
If no flags are present, coding standard fixes will be automatically applied.

To report coding standard problems _without_ modifying files use the `--dry-run` flag.

Additionally, to display a diff of the fixes that would be applied, use the `--diff` flag as well.
:::

**Manual**

```sh
app/vendor/bin/php-cs-fixer fix [--diff] [--dry-run]
```

::: info
If no flags are present, coding standard fixes will be automatically applied.

To report coding standard problems _without_ modifying files use the `--dry-run` flag.

Additionally, to display a diff of the fixes that would be applied, use the `--diff` flag as well.
:::

### Perform static analysis

::: code-group

```sh [Make]
make static-analysis
```

```sh [Composer]
composer exec phpstan analyze
```

```sh [Manual]
app/vendor/bin/phpstan analyze
```

:::

### Generate code coverage report

::: code-group

```sh [Make]
make coverage
```

```sh [Composer]
XDEBUG_MODE=coverage composer exec phpunit --coverage-html .coverage
```

```sh [Manual]
XDEBUG_MODE=coverage app/vendor/bin/phpunit --coverage-html .coverage
```

:::

::: info
Code coverage requires a code coverage engine (e.g. xdebug pr pcov) to run.
:::
