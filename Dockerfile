# Use an official PHP runtime as a parent image
FROM php:8.3-cli-alpine

# Set the working directory
WORKDIR /var/www

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .


