ARG PHP_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer_stage

FROM php:${PHP_VERSION}-fpm-alpine

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

RUN echo -e '\033[1;34m Building extensions... \033[0m \n'   \
    && docker-php-ext-install pdo_mysql > /dev/null; docker-php-ext-configure pdo_mysql > /dev/null \
    && apk add --no-cache --update --virtual buildDeps build-base autoconf \
    && pecl install xdebug > /dev/null \
    && apk del buildDeps build-base autoconf \
 && echo -e '\033[1;32m ...Extensions OK \033[0m \n'

WORKDIR /app