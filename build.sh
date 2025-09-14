#!/bin/bash
set -e

echo "Starting build process..."

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
echo "Installing Node.js dependencies..."
npm install

# Build frontend assets
echo "Building frontend assets..."
npm run build

# Verify build output
echo "Verifying build output..."
if [ ! -f "public/build/manifest.json" ]; then
    echo "ERROR: manifest.json not found after build!"
    exit 1
fi

echo "Build completed successfully!"
ls -la public/build/
