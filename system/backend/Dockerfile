ARG PHP_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} as composer_stage

FROM php:${PHP_VERSION}-fpm-alpine

RUN echo 'Build extensions'   \
    && docker-php-ext-install pdo_mysql > /dev/null; docker-php-ext-configure pdo_mysql > /dev/null \
&& echo 'Build OK'

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

WORKDIR /app