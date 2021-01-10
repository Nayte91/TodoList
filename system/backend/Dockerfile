ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm-alpine

RUN echo 'Build extensions'   \
    && docker-php-ext-install pdo_mysql > /dev/null; docker-php-ext-configure pdo_mysql > /dev/null \
&& echo 'Build OK'

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

WORKDIR /app