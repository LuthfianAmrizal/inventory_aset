# Use PHP 8.2 with Apache as base image
FROM php:8.2-apache

# Set shell to bash for better error handling
SHELL ["/bin/bash", "-c"]

# Install system dependencies and PHP extension libraries
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (required for compiling Vite frontend assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Define default environment variables
ENV PORT=80
ENV APP_ENV=production
ENV APP_DEBUG=false

# Copy only composer and package files first to leverage build cache
COPY composer.json composer.lock ./
COPY package.json package-lock.json* ./

# Install dependencies (without running autoloader/scripts/plugins yet)
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --no-autoloader
RUN npm ci

# Copy the rest of the application files
COPY . .

# Run production asset compilation
RUN npm run build

# Generate autoload files and run composer dump-autoload scripts (like package:discover)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set directory permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Overwrite Apache virtual host configuration to use Laravel's public directory
RUN printf '<VirtualHost *:${PORT}>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>\n' > /etc/apache2/sites-available/000-default.conf

# Overwrite Apache ports configuration to use dynamic PORT variable
RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf

# Expose port 80 (standard HTTP port, Render will map custom PORT at runtime)
EXPOSE 80

# Create a startup script to run caching commands and start Apache at runtime
RUN printf '#!/bin/sh\n\
set -e\n\
\n\
# Ensure storage subdirectories exist and have correct permissions\n\
mkdir -p /var/www/html/storage/framework/cache/data\n\
mkdir -p /var/www/html/storage/framework/sessions\n\
mkdir -p /var/www/html/storage/framework/views\n\
mkdir -p /var/www/html/storage/logs\n\
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache\n\
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache\n\
\n\
# Dynamically replace port 80 with $PORT in configuration files at boot time\n\
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf\n\
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf\n\
\n\
# Warm up Laravel caches\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
php artisan view:cache || true\n\
\n\
# Execute the base image entrypoint\n\
exec apache2-foreground\n' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Use start.sh as container entrypoint
ENTRYPOINT ["/usr/local/bin/start.sh"]
