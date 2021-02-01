ARG PHP_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer_stage

FROM php:${PHP_VERSION}-fpm-alpine

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

RUN echo -e '\033[1;34m Building PHP extensions... \033[0m \n'   \
    && docker-php-ext-install pdo_mysql > /dev/null; docker-php-ext-configure pdo_mysql > /dev/null \
    && apk add --no-cache --update --virtual buildDeps build-base autoconf > /dev/null; pecl install xdebug > /dev/null; apk del buildDeps build-base autoconf > /dev/null \
 && echo -e '\033[1;34m ...PHP Extensions OK \033[0m \n'

###> Blackfire ###
# Blackfire Build, comment if you don't use
RUN echo -e '\033[1;31m Building Blackfire profiler... \033[0m \n'   \
    && version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && architecture=$(case $(uname -m) in i386 | i686 | x86) echo "i386" ;; x86_64 | amd64) echo "amd64" ;; aarch64 | arm64 | armv8) echo "arm64" ;; *) echo "amd64" ;; esac) \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/$architecture/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get ('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz \
 && echo -e '\033[1;31m ...Blackfire profiler build OK \033[0m \n'

# Please note that the Blackfire Probe is dependent on the session module.
# Activate the session module in docker-compose.yml.
###< Blackfire ###

WORKDIR /app