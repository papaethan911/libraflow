#!/bin/bash
set -e

echo "Starting build process..."

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
echo "Installing Node.js dependencies..."
npm install

# Check if public/build directory exists, create if not
echo "Ensuring public/build directory exists..."
mkdir -p public/build

# Build frontend assets with verbose output
echo "Building frontend assets..."
npm run build --verbose

# Check what was actually created
echo "Checking build output..."
echo "Contents of public/build/:"
ls -la public/build/ || echo "public/build/ directory is empty or doesn't exist"

echo "Contents of public/:"
ls -la public/ | grep -E "(build|manifest)"

# Verify build output
echo "Verifying build output..."
if [ ! -f "public/build/manifest.json" ]; then
    echo "ERROR: manifest.json not found after build!"
    echo "Attempting to find manifest.json in other locations..."
    find . -name "manifest.json" -type f 2>/dev/null || echo "No manifest.json found anywhere"
    exit 1
fi

echo "Build completed successfully!"
ls -la public/build/
