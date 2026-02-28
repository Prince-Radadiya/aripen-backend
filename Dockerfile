FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl

# Install correct MongoDB extension version
RUN pecl install mongodb-1.16.2 \
    && docker-php-ext-enable mongodb

RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html/

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80