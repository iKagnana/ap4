FROM php:8.2-apache

# activate module mod_rewrite
RUN a2enmod rewrite

RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql