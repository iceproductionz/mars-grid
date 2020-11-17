# Dockerfile
FROM php:7.4.1-fpm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir /app

COPY . /app

WORKDIR /app

RUN composer install

