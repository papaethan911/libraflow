# syntax=docker/dockerfile:1

############################
# Frontend build (Vite)
############################
FROM node:18-alpine AS assets
WORKDIR /app

# Copy only what's needed to build assets
COPY package.json package-lock.json* vite.config.js ./
COPY resources ./resources

# Install and build (no dev scripts beyond build needed)
RUN npm ci --no-audit --no-fund \
	&& npm run build

############################
# PHP + Apache runtime
############################
FROM php:8.2-apache

# Install system dependencies and PHP extensions (PostgreSQL, GD, Sodium)
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
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

# Enable Apache mod_rewrite and set document root to public/
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
	/etc/apache2/sites-available/000-default.conf \
	/etc/apache2/apache2.conf \
	/etc/apache2/sites-available/default-ssl.conf || true

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app files
COPY . .

# Copy built Vite assets from node stage
COPY --from=assets /app/public/build ./public/build

# Install PHP dependencies (no dev deps)
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader

# Ensure writable directories
RUN chown -R www-data:www-data storage bootstrap/cache \
	&& chmod -R 775 storage bootstrap/cache

# Add entrypoint to run migrations/config then start Apache
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose web port
EXPOSE 80

# Start via entrypoint (handles migrations) then Apache
ENTRYPOINT ["/entrypoint.sh"] 