# Build application runtime, image page: <https://hub.docker.com/_/php>
FROM --platform=linux/amd64 php:8.0.5-fpm-alpine3.13 as runtime
# install composer, image page: <https://hub.docker.com/_/composer>
COPY --from=composer:2.0.12 /usr/bin/composer /usr/bin/composer

USER root
# copy composer (json|lock) files for dependencies layer caching
#ADD --chown=root:root ./composer.json /app/composer.json
ADD --chown=root:root ./ /app

WORKDIR /app

RUN apk update && apk add postgresql-dev
RUN docker-php-ext-install -j$(nproc) pdo_mysql pcntl pdo_pgsql
RUN docker-php-ext-install -j$(nproc) sockets opcache \
    && echo -e "\nopcache.enable=1\nopcache.enable_cli=1\nopcache.jit_buffer_size=32M\nopcache.jit=1235\n" >> \
    ${PHP_INI_DIR}/conf.d/docker-php-ext-opcache.PHP_INI_DIR

RUN composer install

ENTRYPOINT []

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["/usr/local/sbin/php-fpm", "-F"]