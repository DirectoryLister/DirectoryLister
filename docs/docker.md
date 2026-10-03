# Docker

Starting with Directory Lister v5.0 an [official Docker image is provided](https://hub.docker.com/r/directorylister/directorylister) as `directorylister/directorylister`.

## Running with `docker run`

```bash
docker run --detach [--env ENVIRONMENT_VARIABLE=value] \
    --volume <host_path>:/data --publish <host_port>:80 \
    directorylister/directorylister:5
```

::: warning
Replace `<host_path>` with the path to the directory you'd like to list.

Replace `<host_port>` with the port you would like to expose the application on.
:::

::: info
You may pass one or more environment variables with multiple `--env` flags.

See the [Configuration Reference](./configuration/configuration-reference.md) for a full list of the available environment variables.
:::

## Running with `docker compose`

The following is an example `docker-compose.yaml` file. For more information on `docker compose` and how to use this file see the [Docker Compose documentation](https://docs.docker.com/compose/).

::: code-group

```yaml [docker-compose.yaml]
services:

  directory-lister:
    image: directorylister/directorylister:<version>
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
    restart: unless-stopped
```

:::

::: warning
Replace `<version>` with the version of Directory Lister you'd like to use (e.g. `5.0.5`, `5.0` or `5`)

Replace `<host_path>` with the path to the directory you'd like to list.

Replace `<host_port>` with the port you would like to expose the application on.
:::

::: info
See the [Configuration Reference](./configuration/configuration-reference.md) for a full list of the available environment variables.
:::

### Advanced `docker compose` usage

The following is an example `docker-compose.yaml` file showing Directory Lister being run with a Valkey container for caching.

::: code-group

```yaml [docker-compose.yaml]
services:

  directory-lister:
    image: directorylister/directorylister:<version>
    environment:
      CACHE_DRIVER: redis
      REDIS_HOST: cache
      REDIS_PORT: 6379
      # See configuration docs for additional variables
    ports:
      - <host_port>:80
    volumes:
      - <host_path>:/data
    depends_on: [cache]
    restart: unless-stopped

  cache:
    image: valkey:8
    restart: unless-stopped
```

:::

::: warning
Replace `<version>` with the version of Directory Lister you'd like to use (e.g. `5.0.5`, `5.0` or `5`)

Replace `<host_path>` with the path to the directory you'd like to list.

Replace `<host_port>` with the port you would like to expose the application on.
:::
