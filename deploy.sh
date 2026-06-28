#!/bin/bash
set -e

cd /var/www/trainingsdata

# Pull latest code
sudo chown -R $USER:$USER /var/www/trainingsdata
git pull

# Install dependencies
composer install --optimize-autoloader
npm install
npm run build

# If a composer package update doesn't seem to take effect, run:
# composer clear-cache
# then re-run this deploy script.
# composer install --no-dev --optimize-autoloader
# php artisan config:cache
# php artisan route:cache
# php artisan health:clear

# Laravel
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
sudo chown -R www-data:www-data /var/www/trainingsdata
sudo chmod -R 775 /var/www/trainingsdata/storage
sudo chmod -R 775 /var/www/trainingsdata/bootstrap/cache
