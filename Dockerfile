FROM php:8.2-apache

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80