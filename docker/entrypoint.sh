#!/bin/sh
set -e

# Cache config to speed up and ensure env is loaded
php artisan config:cache || true

# Run database migrations automatically on container start
php artisan migrate --force || true

# Optionally seed admin when SEED_ADMIN=true (one-time)
if [ "$SEED_ADMIN" = "true" ]; then
	php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder || true
fi

# Start Apache HTTPD (from php:apache base image)
exec apache2-foreground 