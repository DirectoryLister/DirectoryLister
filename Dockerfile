FROM php:8.0-apache
LABEL maintainer="Chris Kankiewicz <Chris@ChrisKankiewicz.com>"

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME="/tmp"

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer
COPY .docker/apache/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.ini /usr/local/etc/php/php.ini

RUN a2enmod rewrite

RUN apt-get update && apt-get install --assume-yes libmemcached-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install opcache zip \
    && pecl install apcu memcached redis xdebug \
    && docker-php-ext-enable apcu memcached redis xdebug
