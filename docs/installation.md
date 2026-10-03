# Installation

Installation of Directory Lister is fast and requires no configuration. Starting
with Directory Lister v5.0 an [official Docker image is provided](https://hub.docker.com/r/directorylister/directorylister)
as `directorylister/directorylister`.

## Directory Lister Compose

> [!IMPORTANT] Requirements
> - [Docker Compose](https://docs.docker.com/compose/)
> - [Git Version Control](https://git-scm.com)
> - [GNU Make](https://www.gnu.org/software/make/)

For a long-term installation we recommend [Directory Lister Compose](https://github.com/DirectoryLister/directory-lister-compose),
a pre-packaged [Docker Compose](https://docs.docker.com/compose/) configuration
for quick and easy container management. 

1. Start by cloning the repository to a location of your choosing

    ```console
    git clone https://github.com/DirectoryLister/directory-lister-compose.git /path/to/directory-lister
    ```

2. Switch to the installation directory and initialize the configuration files

    ```console
    cd /path/to/directory-lister
    make init
    ```

3. Modify the environment variables in `.env` for your installation

4. Run `docker compose config` to validate and confirm your configuration

5. Run `docker compose up -d` to start the containers

## Docker Compose

> [!IMPORTANT] Requirements
> - [Docker Compose](https://docs.docker.com/compose/)

The following is an example `docker-compose.yaml` file. For more information on `docker compose` and how to use this file see the [Docker Compose documentation](https://docs.docker.com/compose/).

::: code-group

```yaml [docker-compose.yaml]
services:

  directory-lister:
    image: directorylister/directorylister:latest
    environment:
      # APP_LANGUAGE: en
      # DISPLAY_READMES: true
      # READMES_FIRST: false
      # ZIP_DOWNLOADS: true
      # TIMEZONE: America/Phoenix
      # See configuration docs for additional variables
    ports:
      - <host_port>:80
    volumes:
      - <host_path>:/data
      - app-cache:/var/www/html/cache/app
    restart: unless-stopped
    
volumes:
  app-cache: {}
```

:::

::: important
Replace `<host_path>` with the path to the directory you'd like to list.

Replace `<host_port>` with the port you would like to expose the application on.
:::

::: tip
See the [Configuration Reference](./configuration/configuration-reference.md) for a full list of the available environment variables.
:::

## Docker Run

> [!IMPORTANT] Requirements
> [Docker](https://docs.docker.com)

You may use `docker run` to launch a stand-alone Docker container from the 
official Docker image. You may use this method to test Directory Lister but we
do not recommended this option for long-term use.

```bash
docker run --detach [--env ENVIRONMENT_VARIABLE=value] \
    --volume <host_path>:/data --publish <host_port>:80 \
    directorylister/directorylister:5
```

::: important
Replace `<host_path>` with the path to the directory you'd like to list.

Replace `<host_port>` with the port you would like to expose the application on.
:::

::: tip
You may pass one or more environment variables with multiple `--env` flags.
:::

## Manual Installation

> [!IMPORTANT] Requirements
> - [PHP](https://www.php.net) with the
>   [Zip](https://www.php.net/manual/en/book.zip.php),
>   [DOM](https://www.php.net/en/dom) and
>   [Fileinfo](https://www.php.net/manual/en/book.fileinfo.php) extensions

1. [Download Directory Lister](https://www.directorylister.com)
2. Extract the zip/tar archive
3. Copy extracted files/folders to your web server

## Install with Composer

> [!IMPORTANT] Requirements
> - [PHP](https://www.php.net) with the
>   [Zip](https://www.php.net/manual/en/book.zip.php),
>   [DOM](https://www.php.net/en/dom) and
>   [Fileinfo](https://www.php.net/manual/en/book.fileinfo.php) extensions
> - [Composer](https://getcomposer.org)

```bash
composer create-project phlak/directory-lister
```
