FROM php:8.0-apache
LABEL maintainer="Chris Kankiewicz <Chris@ChrisKankiewicz.com>"

ENV HOME="/tmp"
ENV COMPOSER_HOME="/tmp"
ENV XDG_CONFIG_HOME="/tmp/.config"

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer
COPY --from=node:16.3 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:16.3 /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN ln --symbolic ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln --symbolic ../lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

RUN apt-get update && apt-get install --assume-yes --no-install-recommends \
    libmemcached-dev libzip-dev tar zip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install opcache zip \
    && pecl install apcu memcached redis xdebug \
    && docker-php-ext-enable apcu memcached redis xdebug

RUN a2enmod rewrite

COPY .docker/apache/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.ini /usr/local/etc/php/php.ini
