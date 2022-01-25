FROM php:7.2-apache

RUN apt-get update \
  && apt-get install -y zip libfreetype6-dev libjpeg62-turbo-dev libpng-dev libmcrypt-dev \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv
    #Xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN a2enmod rewrite
RUN a2enmod include

COPY ./base.conf /etc/apache2/sites-available/base.conf
RUN a2ensite base.conf
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
COPY php.ini /usr/local/etc/php/
