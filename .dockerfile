# syntax=docker/dockerfile:1

FROM php:8.2-apache

RUN apt update \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && apt install -y \ 
    git \
    unzip \
    zip

COPY . /var/www/html

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN useradd composer

USER composer

RUN composer install --no-dev

EXPOSE 80