# syntax=docker/dockerfile:1

FROM php:8.2-apache

RUN apt update \
    && docker-php-ext-install pdo_mysql \
    && apt install -y \ 
    git \
    unzip \
    zip

COPY . /var/www/html
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN useradd composer

USER composer

RUN composer install --no-dev

EXPOSE 80