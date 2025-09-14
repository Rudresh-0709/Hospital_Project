# Use official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions (mysqli for MySQL, gd for QR code, etc.)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
FROM php:8.1-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli \
    && apt-get update \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Copy Apache configs if needed
COPY ./src /var/www/html/

# Enable Apache mod_rewrite (often needed in PHP projects)
RUN a2enmod rewrite

# Copy project files into Apache's default web folder
COPY src/ /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose Apache port
EXPOSE 80
