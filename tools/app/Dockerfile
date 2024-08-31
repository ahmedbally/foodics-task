# Dockerfile

# Stage 1: Build stage with Composer
FROM composer:2 AS builder

WORKDIR /var/www

COPY . /var/www

# Install Laravel dependencies
RUN composer install --optimize-autoloader


# Stage 2: Application runtime with PHP-FPM
FROM php:8.2-fpm as runtime

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql gd \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www

# Copy files from the build stage
COPY --from=builder /var/www /var/www

# Ensure storage and cache directories are writable
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy the entrypoint script
COPY ./tools/app/entrypoint.sh /usr/local/bin/entrypoint.sh

# Make the entrypoint script executable
RUN chmod +x /usr/local/bin/entrypoint.sh

# Ensure .env file exists
RUN if [ ! -f ".env" ]; then cp .env.example .env; fi

# Generate APP_KEY if not set
RUN if [ -z "$APP_KEY" ]; then php artisan key:generate; fi

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Use the entrypoint script as the default startup command
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
