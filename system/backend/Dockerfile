ARG PHP_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer_stage

FROM php:${PHP_VERSION}-fpm-alpine

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

RUN echo 'Build extensions'   \
    && docker-php-ext-install pdo_mysql > /dev/null; docker-php-ext-configure pdo_mysql > /dev/null \
 && echo 'Build OK'

WORKDIR /app