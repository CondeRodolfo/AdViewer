#!/bin/bash

# Check if the Laravel project already exists
if [ ! -f "/var/www/html/artisan" ]; then
    echo "Creating new Laravel project..."
    composer create-project --prefer-dist laravel/laravel:^10.0 /tmp/laravel
    cp -r /tmp/laravel/. /var/www/html/
    rm -rf /tmp/laravel
    
    # Set proper permissions
    chown -R www-data:www-data /var/www/html
    chmod -R 755 /var/www/html/storage
    chmod -R 755 /var/www/html/bootstrap/cache
    
    # Install required packages
    composer require guzzlehttp/guzzle
    
    # Clear cache and optimize
    php artisan config:clear
    php artisan cache:clear
fi

# Run migrations
php artisan migrate --force

# Start Apache
apache2-foreground 