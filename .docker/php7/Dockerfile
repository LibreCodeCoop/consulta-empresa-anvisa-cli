FROM php:7.3-fpm

RUN apt-get update

RUN apt-get install -y git libpq-dev libzip-dev \
    && docker-php-ext-install zip

RUN pecl install xdebug-2.7.0RC2 \
    && docker-php-ext-enable xdebug

COPY config/php.ini /usr/local/etc/php/conf.d/

WORKDIR /app
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
