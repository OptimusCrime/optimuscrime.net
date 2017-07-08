FROM php:7.1.3-apache

# Copy the PHP settings into place
COPY .docker/php.ini /usr/local/etc/php/

# Install composer
RUN cd /usr/src && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable mod_rewrite
RUN a2enmod rewrite

# Install zlib (required for Composer stuff)
RUN apt-get update && apt-get install -y zlib1g-dev

# Rewrite Apache document root location
COPY .docker/000-default.conf /etc/apache2/sites-available/

# Install PHP extensions
RUN docker-php-ext-install zip
