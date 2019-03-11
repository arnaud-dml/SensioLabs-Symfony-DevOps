FROM php:7.3-fpm

RUN apt-get -y update \
    && apt-get -y upgrade \
    && apt-get install -y libicu-dev libzip-dev zlib1g-dev chromium \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-install pdo_mysql intl opcache zip

ENV PANTHER_NO_SANDBOX 1

# Xdebug
# > https://github.com/prooph/docker-files
# > https://github.com/maciejslawik/docker-php-fpm-xdebug
# > https://xdebug.org/download.php
RUN pecl install xdebug-2.7.0RC2 \
    && docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN chmod a+x /usr/local/bin/composer

COPY ./conf/php.ini /usr/local/etc/php/conf.d/php-custom.ini

WORKDIR /var/www/html/
