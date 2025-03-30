#!/bin/bash

# === ESSENTIAL STEP 1: Set proper permissions ===
# This fixes the "Permission denied" error for Laravel logs
echo "Setting up permissions..."
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
touch /var/www/html/storage/logs/laravel.log
chmod 666 /var/www/html/storage/logs/laravel.log

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
php artisan key:generate --no-interaction --force

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