#!/bin/sh
set -e

# Ensure Laravel storage directories exist and are writable
mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Ensure public/storage symlink exists for serving uploaded/generated files (e.g., QR codes)
php artisan storage:link || true

# Cache config to speed up and ensure env is loaded
php artisan config:cache || true

# Run database migrations automatically on container start
php artisan migrate --force || true

# Optional seeders controlled by env flags
if [ "$SEED_ADMIN" = "true" ]; then
	php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force || true
fi
if [ "$SEED_REAL_BOOKS" = "true" ]; then
	php artisan db:seed --class=Database\\Seeders\\RealBooksSeeder --force || true
fi

# Start Apache HTTPD (from php:apache base image)
exec apache2-foreground 