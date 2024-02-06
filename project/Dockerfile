FROM php:8.1-apache

RUN apt-get update -y && apt-get install -y
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli
# Install Composer
# Install composer
6
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


RUN pecl install apcu && docker-php-ext-enable apcu
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git
RUN git config --global user.email "sanabenhendalaajimi@gmail.com" \
    &&  git config --global user.name "sanaben25"
RUN a2enmod rewrite

WORKDIR /var/www/