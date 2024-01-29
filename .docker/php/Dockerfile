FROM php:8.3-apache

RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install zip

RUN apt-get update && apt-get upgrade -y && apt-get install -y git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

