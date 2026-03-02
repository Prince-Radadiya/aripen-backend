FROM php:8.2-apache

# Install required system dependencies (INCLUDING SSL)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libssl-dev \
    pkg-config

# Install MongoDB extension WITH SSL support
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Enable Apache rewrite
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html/

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80