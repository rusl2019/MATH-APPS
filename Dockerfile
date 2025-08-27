FROM php:7.4.33-apache
WORKDIR /var/www/html/

RUN apt-get update && apt-get upgrade -y
RUN apt install -y zlib1g* libpng* libzip-dev zip libicu-dev pkg-config

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install gd     && docker-php-ext-enable gd
RUN docker-php-ext-install zip    && docker-php-ext-enable zip
RUN docker-php-ext-install intl   && docker-php-ext-enable intl
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN a2enmod rewrite
