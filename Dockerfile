# --------- BASE ----------

FROM php:8.5-apache AS base
LABEL maintainer="Chris Kankiewicz <Chris@Kankiewicz.com>"

EXPOSE 80
RUN a2enmod rewrite

# ENV HOME="/home/app"
# ENV COMPOSER_HOME="${HOME}/.config/composer"
# ENV XDG_CONFIG_HOME="${HOME}/.config"

ENV HOME="/tmp"
ENV COMPOSER_HOME="/tmp"
ENV XDG_CONFIG_HOME="/tmp/.config"

COPY .docker/apache2/config/000-default.dev.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.dev.ini /usr/local/etc/php/php.ini

COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer
COPY --from=node:25.8 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:25.8 /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN ln --symbolic ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN ln --symbolic ../lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

RUN apt-get update && apt-get install --assume-yes --no-install-recommends \
    git libmemcached-dev libssl-dev make zip zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# RUN docker-php-ext-install zip

RUN pecl install apcu \
    && pecl install memcached \
    && pecl install redis

RUN docker-php-ext-enable apcu memcached redis

# --------- BUILD ----------

FROM base AS build

COPY ./ /var/www/html
WORKDIR /var/www/html

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
RUN npm install --no-save && npm run build && npm prune --production

RUN make production

# --------- PROD ----------

FROM base AS prod

# ENV FILES_PATH="/data"

COPY .docker/apache2/config/000-default.prod.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.prod.ini /usr/local/etc/php/php.ini

COPY --from=base /usr/local/bin/node /usr/local/bin/node
COPY --from=base /usr/local/lib/node_modules /usr/local/lib/node_modules

COPY --from=build /var/www/html /var/www/html
RUN chown --recursive www-data:www-data /var/www/html

VOLUME /var/www/html/app/cache

# --------- DEV ----------

FROM prod AS dev

COPY .docker/apache2/config/000-default.dev.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.dev.ini /usr/local/etc/php/php.ini

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
