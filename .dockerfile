# syntax=docker/dockerfile:1
FROM php:8.2-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
EXPOSE 80
EXPOSE 3306