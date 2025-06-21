FROM php:7.4-apache

# Инсталирај потребни библиотеки
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mysqli \
        mbstring \
        gd \
        iconv \
        zip

# Вклучи Apache модули
RUN a2enmod rewrite include

# Копирај Apache конфигурација
COPY ./base.conf /etc/apache2/sites-available/base.conf
RUN a2ensite base.conf

# Копирај custom php.ini
COPY php.ini /usr/local/etc/php/
