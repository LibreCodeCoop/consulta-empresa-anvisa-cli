FROM php:7.3-fpm

RUN apt-get update

RUN apt-get install -y \
        git \
        libpq-dev \
        libzip-dev \
		libjpeg-dev \
		libpng-dev
RUN docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr;
RUN docker-php-ext-install -j "$(nproc)" \
        zip \
        gd

RUN pecl install xdebug-2.7.0RC2 \
    && docker-php-ext-enable xdebug

COPY config/php.ini /usr/local/etc/php/conf.d/

WORKDIR /app
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
