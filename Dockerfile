FROM php:7.4-apache
LABEL maintainer="Chris Kankiewicz <Chris@ChrisKankiewicz.com>"

COPY .docker/apache/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.ini /usr/local/etc/php/php.ini

RUN a2enmod rewrite
RUN pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /var/www/html
