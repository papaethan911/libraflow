# Multi-stage build for Laravel with frontend assets
FROM node:18-alpine AS frontend

# Set working directory for frontend build
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install ALL dependencies (including dev dependencies needed for build)
RUN npm ci

# Copy frontend source files
COPY resources/ ./resources/
COPY tailwind.config.js postcss.config.js vite.config.js ./

# Build frontend assets
RUN npm run build

# PHP stage
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsodium-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd sodium \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built frontend assets from frontend stage
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set proper permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create storage directories
RUN mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views

# Create Apache configuration file
COPY <<EOF /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog \/error.log
    CustomLog \/access.log combined
</VirtualHost>
EOF

# Create startup script
COPY <<EOF /startup.sh
#!/bin/bash
set -e
php artisan config:cache
php artisan migrate --force
php artisan storage:link
if [ "\" = "true" ]; then
    php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force
fi
if [ "\" = "true" ]; then
    php artisan db:seed --class=Database\\Seeders\\RealBooksSeeder --force
fi
if [ "\" = "true" ]; then
    php artisan db:seed --class=Database\\Seeders\\SystemSettingsSeeder --force
fi
exec apache2-foreground
EOF

RUN chmod +x /startup.sh

# Expose port 80
EXPOSE 80

# Start with our custom script
CMD ["/startup.sh"]
