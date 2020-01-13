# Install PHP dependencies
FROM composer:1.9 AS php-dependencies
COPY . /application
RUN composer install --working-dir /application --ignore-platform-reqs \
    --no-cache --no-dev --no-interaction

# Install and compile JavaScript assets
FROM node:13.6 AS js-dependencies
COPY --from=php-dependencies /application /application
RUN cd /application && npm install && npm run production

# Build application image
FROM php:7.4-apache as application
LABEL maintainer="Chris Kankiewicz <Chris@ChrisKankiewicz.com>"

COPY --from=js-dependencies /application /var/www/html

RUN a2enmod rewrite

# Build development image
FROM application as development
COPY ./.docker/php/config/php.dev.ini /usr/local/etc/php/php.ini
COPY ./.docker/apache2/config/000-default.dev.conf /etc/apache2/sites-available/000-default.conf
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Build production image
FROM application as production
COPY ./.docker/php/config/php.prd.ini /usr/local/etc/php/php.ini
COPY ./.docker/apache2/config/000-default.prd.conf /etc/apache2/sites-available/000-default.conf
RUN docker-php-ext-install opcache
