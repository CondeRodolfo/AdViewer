#!/bin/bash

# === ESSENTIAL STEP 1: Set proper permissions ===
# This fixes the "Permission denied" error for Laravel logs
echo "Setting permissions for storage and logs..."
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
touch /var/www/html/storage/logs/laravel.log
chmod 666 /var/www/html/storage/logs/laravel.log

# === NEW STEP: Copy .env.example to .env if .env doesn't exist ===
if [ ! -f "/var/www/html/.env" ] && [ -f "/var/www/html/.env.example" ]; then
    echo "Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
    chmod 666 /var/www/html/.env
fi

# === ESSENTIAL STEP 2: Install Laravel if not present ===
if [ ! -f "/var/www/html/artisan" ]; then
    echo "Installing Laravel..."
    composer create-project --prefer-dist laravel/laravel:^10.0 /tmp/laravel
    cp -r /tmp/laravel/. /var/www/html/
    rm -rf /tmp/laravel
    composer require guzzlehttp/guzzle
fi

# === ESSENTIAL STEP 3: Generate app key ===
# This fixes the "No application encryption key has been specified" error
if [ -f "/var/www/html/.env" ]; then
    php artisan key:generate --no-interaction --force
else
    echo "ERROR: .env file not found and could not be created!"
fi

# === ESSENTIAL STEP 4: Simple database connection handling ===
echo "Checking database connection..."
if php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; then
    echo "Database connected, running migrations..."
    php artisan migrate --force
else
    echo "Database not available, skipping migrations..."
fi

# === ESSENTIAL STEP 5: Start Apache server ===
apache2-foreground 