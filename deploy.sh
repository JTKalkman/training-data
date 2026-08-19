#!/bin/bash
set -e

APP_DIR="/var/www/trainingsdata"

cd "$APP_DIR"

sudo chown -R $USER:$USER "$APP_DIR"

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
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 775 "$APP_DIR/storage"
sudo chmod -R 775 "$APP_DIR/bootstrap/cache"

# Make sure the scheduler cron entry exists, running as www-data
CRON_FILE="/etc/cron.d/trainingsdata-scheduler"
CRON_LINE="* * * * * www-data cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1"

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
