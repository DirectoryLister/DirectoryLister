# Install PHP dependencies
FROM composer:1.9 AS php-dependencies
COPY . /app
RUN composer install --working-dir /app --ignore-platform-reqs \
    --no-cache --no-dev --no-interaction

# Install and compile JavaScript assets
# FROM node:12.10 AS js-dependencies
# ARG FONT_AWESOME_TOKEN
# COPY --from=php-dependencies /app /app
# RUN npm config set "@fortawesome:registry" https://npm.fontawesome.com/
# RUN npm config set "//npm.fontawesome.com/:_authToken" ${FONT_AWESOME_TOKEN}
# RUN cd /app && npm install && npm run production

# Build application image
FROM php:7.3-apache as application
LABEL maintainer="Chris Kankiewicz <ckankiewicz@freedomdebtrelief.com>"

COPY --from=php-dependencies /app /var/www/html

RUN a2enmod rewrite

# Build (local) development image
FROM application as development
COPY ./.docker/php/config/php.dev.ini /usr/local/etc/php/php.ini
COPY ./.docker/apache2/config/000-default.dev.conf /etc/apache2/sites-available/000-default.conf
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Build production image
FROM application as production
COPY ./.docker/php/config/php.prd.ini /usr/local/etc/php/php.ini
COPY ./.docker/apache2/config/000-default.prd.conf /etc/apache2/sites-available/000-default.conf
RUN docker-php-ext-install opcache
