# Development Environment

## Requirements

* [PHP](https://php.net) with the `zip`, `dom` and `fileinfo` (and optionally `apcu`, `memcached`, `redis`) extensions
  * [Composer](https://getcomposer.org) for PHP dependency management
* [NPM](https://www.npmjs.com) for front end asset serving and bundling
* [Docker](https://www.docker.com) and [Docker Compose](https://docs.docker.com/compose/) for running the local development container
* [Git](https://git-scm.com/) for version control

## Instructions

::: warning
These instructions are for setting up a local DEVELOPMENT environment. If you are looking for basic installation instruction see the [Installation](../installation.md) page instead.
:::

1. [Fork the Directory Lister repository to your own account](https://github.com/DirectoryLister/DirectoryLister/fork) (optional)
2. [Clone Directory Lister to a local repository](https://help.github.com/en/github/creating-cloning-and-archiving-repositories/cloning-a-repository)

    ```bash
    git clone https://github.com/DirectoryLister/DirectoryLister.git
    ```

3. Switch to the Directory Lister directory

    ```bash
    cd /path/to/DirectoryLister
    ```

4. Install and build PHP and JavaScript dependencies

    ```bash
    composer install
    npm install
    ```

5. Run the local Docker container

    ```bash
    docker-compose up -d
    ```

You should now be able to access your local Directory Lister installation at <http://localhost>.

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
make test
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
This checks coding standards and reports problems _without_ modifying files.

See the Composer or Manual tab to automatically apply coding standard fixes.
:::

**Composer**

```sh
composer exec php-cs-fixer fix [--diff] [--dry-run]
```

::: tip
If no flags are present, coding standard fixes will be automatically applied.

To report coding standard problems _without_ modifying files use the `--dry-run` flag.

Additionally, to display a diff of the fixes that would be applied, use the `--diff` flag as well.
:::

**Manual**

```sh
app/vendor/bin/php-cs-fixer fix [--diff] [--dry-run]
```

::: tip
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

::: important
Code coverage requires a code coverage engine (e.g. xdebug or pcov) to run.
:::
