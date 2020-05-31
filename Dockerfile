FROM php:7.4-apache
LABEL maintainer="Chris Kankiewicz <Chris@ChrisKankiewicz.com>"

COPY .docker/apache/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.ini /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install --assume-yes libmemcached-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip \
    && pecl install memcached redis xdebug \
    && docker-php-ext-enable memcached redis xdebug

RUN a2enmod rewrite

WORKDIR /var/www/html
