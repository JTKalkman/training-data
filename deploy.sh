#!/bin/bash
set -e

cd /var/www/trainingsdata

sudo chown -R $USER:$USER /var/www/trainingsdata

# Pull latest code from a known branch, not whatever's checked out
git checkout main
git pull origin main

# Maintenance mode
php artisan down --secret="$(openssl rand -hex 8)" || true

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Laravel
php artisan config:clear
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Bring workers/php-fpm up to date with new code
sudo systemctl reload php8.3-fpm.service
php artisan queue:restart

# Fix permissions
sudo chown -R www-data:www-data /var/www/trainingsdata
sudo chmod -R 775 /var/www/trainingsdata/storage
sudo chmod -R 775 /var/www/trainingsdata/bootstrap/cache

# Back up
php artisan up
