#!/bin/bash
set -e

cd /var/www/trainingsdata

sudo chown -R $USER:$USER /var/www/trainingsdata

# Make sure we are on the main branch and pull the latest changes
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

# Fix permissions
sudo chown -R www-data:www-data /var/www/trainingsdata
sudo chmod -R 775 /var/www/trainingsdata/storage
sudo chmod -R 775 /var/www/trainingsdata/bootstrap/cache


# Make sure the scheduler cron entry exists, running as www-data
CRON_FILE="/etc/cron.d/trainingsdata-scheduler"
CRON_LINE="* * * * * www-data cd /var/www/trainingsdata && php artisan schedule:run >> /dev/null 2>&1"

if [ ! -f "$CRON_FILE" ] || ! grep -qF "artisan schedule:run" "$CRON_FILE"; then
    echo "$CRON_LINE" | sudo tee "$CRON_FILE" > /dev/null
    sudo chmod 644 "$CRON_FILE"
    echo "Installed scheduler cron entry for www-data."
fi

# Bring workers/php-fpm up to date with new code
sudo systemctl reload php8.3-fpm.service
php artisan queue:restart

# Back up
php artisan up
