# Use PHP 8.2 with Apache
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

# Create custom Apache configuration for Laravel
RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-default.conf \
    && echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    ErrorLog /error.log' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    CustomLog /access.log combined' >> /etc/apache2/sites-available/000-default.conf \
    && echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set proper permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create storage directories
RUN mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views

# Create startup script
RUN echo '#!/bin/bash' > /startup.sh \
    && echo 'set -e' >> /startup.sh \
    && echo 'php artisan config:cache' >> /startup.sh \
    && echo 'php artisan migrate --force' >> /startup.sh \
    && echo 'php artisan storage:link' >> /startup.sh \
    && echo 'if [ "" = "true" ]; then' >> /startup.sh \
    && echo '    php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force' >> /startup.sh \
    && echo 'fi' >> /startup.sh \
    && echo 'if [ "" = "true" ]; then' >> /startup.sh \
    && echo '    php artisan db:seed --class=Database\\Seeders\\RealBooksSeeder --force' >> /startup.sh \
    && echo 'fi' >> /startup.sh \
    && echo 'if [ "" = "true" ]; then' >> /startup.sh \
    && echo '    php artisan db:seed --class=Database\\Seeders\\SystemSettingsSeeder --force' >> /startup.sh \
    && echo 'fi' >> /startup.sh \
    && echo 'exec apache2-foreground' >> /startup.sh \
    && chmod +x /startup.sh

# Expose port 80
EXPOSE 80

# Start with our custom script
CMD ["/startup.sh"]
