FROM php:8.2-fpm
# Install pdo_mysql extension
RUN docker-php-ext-install pdo_mysql
